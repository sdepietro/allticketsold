@extends('personas.layouts.app')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.1/showdown.min.js"></script>
<base href="{{ config('app.url') }}/recursos/" />
    <div class="flex min-h-screen justify-between flex-col bgLight">
        <div class="flex flex-col justify-between h-full">
      
          
          <div class="">
            <form class="flex flex-col justify-center items-center">
              <div class="flex flex-col items-center gap-4 containerRegistro">
                <div class="flex items-center">
                    <img class="w-18 h-18" src="{{ config('app.url') }}/recursos/public/terminosycondiciones.png" />
                    <span class="flex textColor gap-1 sectionName pl-10 uppercase font-bold">
                      Políticas de Privacidad
                    </span>
                </div>
              <div   class="containerTerminos textColor">
                    <span id="content">
					
					  <!--<div id="editor" class="cm-s-paper containerTerminos textColor"></div>-->
            </span>
                </div>
              </div>
            </form>
          </div>
<script>
        
		/*document.addEventListener("DOMContentLoaded", function() {
            // Obtener el contenido de la sesión
            var markdownContent = `{{ addslashes($organiser->about) }}`;

            // Convertir Markdown a HTML
            var htmlContent = marked(markdownContent);

            // Insertar HTML en el div
            document.getElementById("content").innerHTML = htmlContent;
        });*/
		
		const converter = new showdown.Converter();
		const markdown = `{{ addslashes($organiser->tax_id) }}`;
		const formattedMarkdown = markdown.replace(/\n/g, '<br>');
		const html = converter.makeHtml(formattedMarkdown);
		document.getElementById("content").innerHTML = html;
    </script>
	<script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ config('app.url') }}/recursos/animationDropdown.js"></script>
@endsection
