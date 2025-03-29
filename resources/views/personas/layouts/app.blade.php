<!DOCTYPE html>
<html lang="es">
  <head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ALL Tickets</title>
<link rel="shortcut icon" href="{{ config('app.url') }}/assets/images/fav2/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('app.url') }}/assets/images/fav2/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('app.url') }}/assets/images/fav2/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('app.url') }}/assets/images/fav2/favicon-16x16.png">
    <link rel="manifest" href="{{ config('app.url') }}/assets/images/fav2/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ config('app.url') }}/recursos/styles/stylesGlobals.css" />
    <link rel="stylesheet" href="{{ config('app.url') }}/recursos/styles/stylesResponsive.css" />
    <script>
        function resizeIframe(obj) {
          obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
      </script>
  </head>

<body>
	@include('personas.partials.header')
	@yield('content')

    <!-- Incluir el footer -->
    @include('personas.partials.footer')

	</body>
</html>