<meta charset="utf-8">
<title>SISOLMAR | {{ $title }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
<meta content="MyraStudio" name="author">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" type="image/png" href="{{ asset('images/icono.png') }}">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script src="https://kit.fontawesome.com/76256ea07c.js" crossorigin="anonymous"></script>
<script>
    const VITE_URL_APP = '{{ env('VITE_URL') }}';
    window.abrirModalPasswordChange = () => {
        document.getElementById("btn-modal-password-change-user").click();
    }
</script>
