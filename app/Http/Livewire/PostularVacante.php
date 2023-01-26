<?php

namespace App\Http\Livewire;

use App\Models\Vacante;
use App\Notifications\NuevoCandidato;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostularVacante extends Component
{
    use WithFileUploads;
    public $cv;
    public $vacante;
     protected $rules =[
        'cv' => 'required|mimes:pdf'
     ];

     public function mount(Vacante $vacante)
     {
        // dd($vacante);
         $this->vacante =$vacante;
     }
     public function postularme()
     {
       
        $datos = $this->validate();

        //almacenar la imagen
        $cv = $this->cv->store('public/cv');
        $datos['cv'] = str_replace('public/cv', '', $cv);

        //almacenar el candidato a la vacante        
        $this->vacante->candidatos()->create([
            'user_id' => auth()->user()->id,
            'cv'=>$datos['cv']
        ]);
        //notificaion ei enviar email
        $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo, auth()->user()->id));
        //mostrar el mensaje que se envio correctamente

        session()->flash('mensaje', 'Se envió tu curriculum correctamente');
        return redirect()->back();
     }

    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
