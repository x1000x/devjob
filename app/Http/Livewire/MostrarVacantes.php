<?php

namespace App\Http\Livewire;

use App\Models\Vacante;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class MostrarVacantes extends Component
{
    protected $listeners = [
        'eliminarVacante'
    ];

    public function eliminarVacante(Vacante $vacante){
        // $vacante->delete();

        {
            if( $vacante->imagen ) {
                Storage::delete('public/vacantes/' . $vacante->imagen);
            }
            if( $vacante->candidatos->count() ) {
                foreach( $vacante->candidatos as $candidato ) {
                    if( $candidato->cv ) {
                        Storage::delete('public/cv/' . $candidato->cv);
                    }
                }
            }
            $vacante->delete();
        }
    }

    public function render()
    {
        $vacantes =Vacante::where('user_id', auth()->user()->id)->paginate(5);
        return view('livewire.mostrar-vacantes', [
            'vacantes'=> $vacantes
        ]);
    }
}
