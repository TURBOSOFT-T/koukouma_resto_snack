<?php

namespace App\Livewire\Developper;

use App\Models\templates;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ListTemplate extends Component
{
    public function render()
    {
        $templates = templates::orderby('id','Desc')->get();
        return view('livewire.developper.list-template',compact("templates"));
    }


    public function use_like_error_meta($id){
        $template = templates::find($id);
        if($template){
            templates::query()->update(['meta_error'=>false]);
            if($template->meta_error == false){
                $template->update(['meta_error'=>true]);
            }
           
        }
    }


    public function delete($id){
        $template=templates::find($id);
        if ($template) {

            if ($template->code) {
                Storage::disk('local')->delete($template->code); 
            };

            $template->delete();
            //flash message
            session()->flash('message', 'Template supprimé avec succès');
        }
    }
}
