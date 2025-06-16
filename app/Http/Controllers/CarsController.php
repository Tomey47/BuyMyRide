<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarImage;
use App\Services\ImageService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Notification;

class CarsController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Car::with('images')->where('is_approved', true);

        if ($request->filled('make')) {
            $query->where('make', 'like', '%' . $request->make . '%');
        }

        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('body_type')) {
            $query->where('body_type', $request->body_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('mileage')) {
            $query->where('mileage', '<=', $request->mileage);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('year', 'asc');
                break;
            case 'mileage_asc':
                $query->orderBy('mileage', 'asc');
                break;
            case 'mileage_desc':
                $query->orderBy('mileage', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $cars = $query->paginate(7);
        return view('cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        if (!$car->is_approved) {
            abort(404);
        }
        $car->load(['user', 'images']);
        return view('cars.show', compact('car'));
    }

    public function store(Request $request)
    {
        $status = $request->input('status', 'published');
        $messages = [
            'make.required' => 'Lūdzu, izvēlieties auto marku',
            'model.required' => 'Lūdzu, izvēlieties auto modeli',
            'year.required' => 'Lūdzu, ievadiet auto gadu',
            'year.integer' => 'Gadam jābūt skaitlim',
            'year.min' => 'Gads nevar būt mazāks par 1900',
            'year.max' => 'Gads nevar būt lielāks par ' . (date('Y') + 1),
            'body_type.required' => 'Lūdzu, izvēlieties auto tipu',
            'transmission.required' => 'Lūdzu, izvēlieties pārnesumkārbu',
            'fuel_type.required' => 'Lūdzu, izvēlieties degvielas veidu',
            'mileage.integer' => 'Nobraukumam jābūt skaitlim',
            'mileage.min' => 'Nobraukums nevar būt negatīvs',
            'price.required' => 'Lūdzu, ievadiet cenu',
            'price.numeric' => 'Cenai jābūt skaitlim',
            'price.min' => 'Cena nevar būt negatīva',
            'location.required' => 'Lūdzu, ievadiet atrašanās vietu',
            'images.array' => 'Attēliem jābūt masīvā',
            'images.max' => 'Varat pievienot maksimāli 6 attēlus',
            'images.*.image' => 'Fails jābūt attēlam',
            'images.*.mimes' => 'Atbalstītie formāti: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'Attēla izmērs nedrīkst pārsniegt 4MB',
        ];

        if ($status === 'draft') {
            $rules = [
                'make' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'body_type' => 'nullable|string|max:255',
                'transmission' => 'nullable|string|max:255',
                'fuel_type' => 'nullable|string|max:255',
                'mileage' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'location' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:2000',
                'show_email' => 'boolean',
                'images' => 'nullable|array|max:6',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ];
        } else {
            $rules = [
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . date('Y'),
                'body_type' => 'required|string|max:255',
                'transmission' => 'required|in:manual,automatic',
                'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
                'mileage' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'location' => 'required|string|max:255',
                'description' => 'nullable|string|max:2000',
                'show_email' => 'boolean',
                'images' => 'required|array|max:6',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'addCar')
                ->withInput();
        }

        $validated = $validator->validated();

        $car = Car::create([
            'user_id'      => Auth::id(),
            'make'         => $validated['make'] ?? null,
            'model'        => $validated['model'] ?? null,
            'year'         => $validated['year'] ?? null,
            'price'        => $validated['price'] ?? null,
            'body_type'    => $request->input('body_type'),
            'transmission' => $request->input('transmission'),
            'fuel_type'    => $request->input('fuel_type'),
            'mileage'      => $request->input('mileage'),
            'color'        => $request->input('color'),
            'description'  => $request->input('description'),
            'location'     => $request->input('location'),
            'show_email'   => $request->has('show_email'),
            'status'       => $status,
        ]);

        if ($status === 'published') {
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'car_created',
                    'message' => 'Jauns auto sludinājums: ' . $car->make . ' ' . $car->model,
                    'car_id' => $car->id
                ]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $this->imageService->compressAndStore($image, 'cars/' . $car->id);
                CarImage::create([
                    'car_id' => $car->id,
                    'image_path' => $path,
                ]);
            }
        }

        $msg = $status === 'draft' ? 'Auto saglabāts kā melnraksts!' : 'Auto veiksmīgi pievienots!';
        return redirect()->route('profile')->with('success', $msg);
    }

    public function edit(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        $status = $request->input('status', 'published');
        $messages = [
            'make.required' => 'Lūdzu, izvēlieties auto marku',
            'model.required' => 'Lūdzu, izvēlieties auto modeli',
            'year.required' => 'Lūdzu, ievadiet auto gadu',
            'year.integer' => 'Gadam jābūt skaitlim',
            'year.min' => 'Gads nevar būt mazāks par 1900',
            'year.max' => 'Gads nevar būt lielāks par ' . (date('Y') + 1),
            'body_type.required' => 'Lūdzu, izvēlieties auto tipu',
            'transmission.required' => 'Lūdzu, izvēlieties pārnesumkārbu',
            'fuel_type.required' => 'Lūdzu, izvēlieties degvielas veidu',
            'mileage.integer' => 'Nobraukumam jābūt skaitlim',
            'mileage.min' => 'Nobraukums nevar būt negatīvs',
            'price.required' => 'Lūdzu, ievadiet cenu',
            'price.numeric' => 'Cenai jābūt skaitlim',
            'price.min' => 'Cena nevar būt negatīva',
            'location.required' => 'Lūdzu, ievadiet atrašanās vietu',
            'images.array' => 'Attēliem jābūt masīvā',
            'images.max' => 'Varat pievienot maksimāli 6 attēlus',
            'images.*.image' => 'Fails jābūt attēlam',
            'images.*.mimes' => 'Atbalstītie formāti: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'Attēla izmērs nedrīkst pārsniegt 4MB',
            'existing_images.array' => 'Esošajiem attēliem jābūt masīvā',
            'existing_images.*.string' => 'Nederīgs attēla ceļš'
        ];

        if ($status === 'draft') {
            $rules = [
                'make' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'body_type' => 'nullable|string|max:255',
                'transmission' => 'nullable|string|max:255',
                'fuel_type' => 'nullable|string|max:255',
                'mileage' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'location' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'show_email' => 'boolean',
                'images' => 'nullable|array|max:6',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'existing_images' => 'nullable|array',
                'existing_images.*' => 'string'
            ];
        } else {
            $rules = [
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'body_type' => 'required|string|max:255',
                'transmission' => 'required|string|max:255',
                'fuel_type' => 'required|string|max:255',
                'mileage' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'location' => 'required|string|max:255',
                'description' => 'nullable|string',
                'show_email' => 'boolean',
                'images' => 'nullable|array|max:6',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
                'existing_images' => 'nullable|array',
                'existing_images.*' => 'string'
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        $existingImages = $request->input('existing_images', []);
        $hasNewImages = $request->hasFile('images');
        $totalImages = count($existingImages) + ($hasNewImages ? count($request->file('images')) : 0);

        if ($status !== 'draft' && $totalImages === 0) {
            $validator->after(function ($validator) {
                $validator->errors()->add('images', 'Jābūt vismaz vienam attēlam.');
            });
        }

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()
                ->withErrors($validator, 'editCar')
                ->withInput()
                ->with('edit_car_id', $car->id);
        }

        $validated = $validator->validated();

        $descriptionChanged = $car->description !== ($validated['description'] ?? null);

        $car->update([
            'make'         => $validated['make'] ?? null,
            'model'        => $validated['model'] ?? null,
            'year'         => $validated['year'] ?? null,
            'body_type'    => $validated['body_type'] ?? null,
            'transmission' => $validated['transmission'] ?? null,
            'fuel_type'    => $validated['fuel_type'] ?? null,
            'mileage'      => $validated['mileage'] ?? null,
            'color'        => $validated['color'] ?? null,
            'price'        => $validated['price'] ?? null,
            'location'     => $validated['location'] ?? null,
            'description'  => $validated['description'] ?? null,
            'show_email'   => $request->has('show_email'),
            'status'       => $status,
        ]);

        $imagesChanged = $request->hasFile('images');

        if ($imagesChanged || $descriptionChanged) {
            $car->is_approved = false;
            $car->save();

            if ($imagesChanged) {
                foreach ($car->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }

                foreach ($request->file('images') as $image) {
                    $path = $this->imageService->compressAndStore($image, 'cars/' . $car->id);
                    CarImage::create([
                        'car_id' => $car->id,
                        'image_path' => $path,
                    ]);
                }
            }

            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $message = '';
                if ($imagesChanged && $descriptionChanged) {
                    $message = 'Auto sludinājuma attēli un apraksts atjaunināti: ' . $car->make . ' ' . $car->model;
                } elseif ($imagesChanged) {
                    $message = 'Auto sludinājuma attēli atjaunināti: ' . $car->make . ' ' . $car->model;
                } elseif ($descriptionChanged) {
                    $message = 'Auto sludinājuma apraksts atjaunināts: ' . $car->make . ' ' . $car->model;
                }

                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'car_updated',
                    'message' => $message,
                    'car_id' => $car->id
                ]);
            }
        } else {
            if ($request->hasFile('images')) {
                foreach ($car->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }

                foreach ($request->file('images') as $image) {
                    $path = $this->imageService->compressAndStore($image, 'cars/' . $car->id);
                    CarImage::create([
                        'car_id' => $car->id,
                        'image_path' => $path,
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Auto veiksmīgi atjaunināts!']);
        }

        $msg = $status === 'draft' ? 'Auto saglabāts kā melnraksts!' : 'Auto veiksmīgi atjaunināts!';
        return redirect()->route('profile')->with('success', $msg);
    }

    public function destroy(Car $car)
    {
        if ($car->user_id !== Auth::id()) {
            abort(403);
        }

        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $car->delete();

        if (request('redirect') === 'profile') {
            return redirect()->route('profile')->with('success', 'Auto veiksmīgi dzēsts!');
        }

        return redirect()->route('cars.index')->with('success', 'Auto veiksmīgi dzēsts!');
    }

    public function report(Request $request, Car $car)
    {
        if (Auth::id() === $car->user_id) {
            return redirect()->back()->with('error', 'Jūs nevarat ziņot par savu sludinājumu.');
        }
        if ($car->is_reported) {
            return redirect()->back()->with('info', 'Šis sludinājums jau ir ziņots.');
        }
        $car->is_reported = true;
        $car->save();
        return redirect()->back()->with('success', 'Sludinājums ziņots administratoram!');
    }
}
