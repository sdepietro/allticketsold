@extends('personas.layouts.app')
@section('content')
<base href="{{ config('app.url') }}/recursos/" />
    <div class="flex min-h-screen justify-between flex-col bgLight">
        <div class="flex flex-col justify-between h-full">
      
          
          <div class="">
            <form class="flex flex-col justify-center items-center" style="margin-bottom: 60px;">
              <div class="flex flex-col items-center gap-4 containerPreg">
                <div class="flex items-center">
                    <img class="w-18 h-18" src="{{ config('app.url') }}/recursos/public/pregIcon.png" />
                    <span class="flex textColor gap-1 sectionName pl-10">
                      Preguntas Frecuentes
                    </span>
                </div>
                <div class="containerFormSobrePreg">
                    <div class="flex gap-3 flex-col">
					@foreach($preguntas as $index => $pregunta)
					<div>
						<div class="flex items-center bgOpciones px-5 py-4 justify-between">
							<span class="font-bold">{{ $pregunta->pregunta }}</span>
							<img class="iconOpciones cursor-pointer btnMenos" src="{{ config('app.url') }}/recursos/public/menosIcon.png" alt="">
						</div>
						@if($index == 0)
						<div class="bgDropDown active">
						@else
						<div class="bgDropDown">
						@endif
							<span class="font-bold">
								{{ $pregunta->respuesta }}
							</span>
						</div>
					</div>

					<!-- Verifica si el índice es 2 (es decir, hemos mostrado 3 elementos) -->
					@if($index == 2)
						</div>
						<div class="flex gap-3 flex-col"> <!-- Cierra el primer div y abre el segundo -->
					@endif
					@endforeach
                </div>
              </div>
            </form>
          </div>
        </div>
     
         
     
    </div>

	<script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ config('app.url') }}/recursos/animationDropdown.js"></script>
@endsection
