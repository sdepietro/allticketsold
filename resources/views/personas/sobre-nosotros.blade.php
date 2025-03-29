@extends('personas.layouts.app')
@section('content')
	<style>
	 @media (max-width: 600px) {
       .imagLogo {
        width: 300px !important;
    }
	}
	</style>

    <div class="flex min-h-screen justify-between flex-col bgLight">
        <div class="flex flex-col justify-between h-full">
    
          
           <div class="">
            <form class="flex flex-col justify-center items-center">
              <div class="flex flex-col items-center gap-4 containerRegistro">
                <div class="flex items-center">
                    <img class="w-18 h-18" src="{{ config('app.url') }}/recursos/public/sobrenos/iconTitle.png" />
                    <span class="flex textColor gap-1 sectionName pl-10">
                      Sobre Nosotros
                    </span>
                </div>
                <div class="containerFormSobreNos" style="display: block;">
                 
                  <div class="flex flex-col textColor text-xl" style="display: flex;align-content: center;align-items: center;">
				  <div>
                    {{ $organiser->phone }}
					</div>
					<div style="margin-top: 40px;">
					<img class="imagLogo" src="{{ config('app.url') }}/recursos/public/logoMain2.png" alt="">
					</div>
                  </div>
                </div>
                
              </div>
            </form>
          </div>
        </div>
 @endsection