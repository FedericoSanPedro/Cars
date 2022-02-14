<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Rules\Uppercase;
use App\Http\Requests\CreateValidationRequest;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*Usando Json 
        borro todos los $car que tenga en index.php y pongo arriba de section
        @foreach($cars as $car)
            {{ $car['name'] }}
        @endforeach

        En Car.php:
        protected $hidden = ['updated_at']

        protected $visible = ['name', 'founded', 'decription', 'created_at']

        Aqui escribo:
        $cars = Car::all()->toJson();
        $cars = json_decode($cars);


        Usando Array
        Todo lo mismo que arriba modificando solo esta linea

        $cars = Car::all()->toArray();
        
        */

        $cars = Car::all();
 
        return view('cars.index',[
            'cars' => $cars
        ]);
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(CreateValidationRequest $request)
    {
        /*
        Si es valido, continua
        Si no es valido tira un ValidationException
        */

        $request->validated();

        /*
        Imagenes:
        guessExtension()
        getMimeType()
        store()
        asStore()
        storePublicly()
        move()
        getClientOriginalName()
        getClientMimeType()
        guesscClientExtension()
        getSize()
        getError()
        isValid()

        $test = request->file('image')->guessExtension();
        dd($test);
        */

        $newImageName = time() . '-' . $request->name
         . '.' . $request->image->extension();

        $request->image->move(public_path('images'),$newImageName);

        /*
        $car = new Car;
        $car->name = $request->input('name');
        $car->founded = $request->input('founded');
        $car->description = $request->input('description');
        $car->save();
        */

        $car = Car::create([
            'name' => $request->input('name'),
            'founded' => $request->input('founded'),
            'description' => $request->input('description'),
            'image_path' => $newImageName

        ]);

        return redirect('/cars');
    }

    public function show($id)
    {
        $car = Car::find($id);

        return view('cars.show')->with('car',$car);
    }

    public function edit($id)
    {
        $car = Car::find($id);

        return view('cars.edit')->with('car',$car);
    }

    public function update(CreateValidationRequest $request, $id)
    {
        $request->validated();

        $car = Car::where('id',$id)
            ->update([
                'name' => $request->input('name'),
                'founded' => $request->input('founded'),
                'description' => $request->input('description'),
                'image_path' => $newImageName
        ]);
        return redirect('/cars');
    }

    public function destroy(Car $car)
    {
        //dd($id) Con esto puedo saber si me esta llegan el id que quiero
        
        //$car = Car::find($id); Si lo dejo por default el metodo, viene id por parametro y necesitare de esta linea para que funcione el metodo

        $car->delete();

        return redirect('/cars');
    }
}
