@extends('layouts.vertical', ['title' => 'Gestion de cargo'])

@section('css')

@endsection

@section('content')

@include("layouts.shared/page-title", ["subtitle" => "Gestion de Legajos", "title" => "Gestión de legajos"])
<script src="https://kit.fontawesome.com/76256ea07c.js" crossorigin="anonymous"></script>

<style>
    .disabled-table {
    pointer-events: none; /* Bloquea clics y eventos */
    opacity: 0.3; /* Reduce la visibilidad */
}
</style>
<div class="grid lg:grid-cols-2 gap-6 mt-8">

    <div class="card">
        <div class="card-header flex gap-1 justify-between items-center">
            <h3 class="text-center card-title">SELECCIONAR CLIENTE</h3>
            <input type="text" placeholder="Buscar cliente..." id="buscarCliente" class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm">

        </div>
        <div class="w-full px-5 py-2 ">
            
            <div id="tblCliente" class="w-full mt-5  overflow-y-auto overflow-x-hidden"></div>
        </div>
       
       
    </div> <!-- end card -->

    <div class="card">
        <div class="card-header flex gap-1 justify-between items-center">
            <h3 class="text-center card-title">SELECCIONAR CARGO</h3>
            <input type="text" id="buscarCargo" placeholder="Buscar cargo..." class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm" />
        </div>
        <div class="w-full px-5 py-2">
        
            <div id="tblCargo" class="w-full mt-5 overflow-y-auto overflow-x-hidden"></div>
        </div>  
    </div> <!-- end card -->

    <div class="card col-span-2">
        <div class="card-header flex gap-1 justify-between items-center">
            <h4 class="card-title">ASIGNAR FOLIOS</h4>
            <input type="text" id="buscarFolio" placeholder="Buscar folios..." class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm" />

        </div>

 

        <div class="w-full px-5 py-2 "  >
        
            <div id="tblFolio" class="w-full mt-5 overflow-y-auto overflow-x-hidden"></div>

           
            
        </div> 
        <input type="hidden" name="hola" id="hidLegajo">
        <div class="w-full px-5 py-2">
            <div class="flex justify-center items-center py-5 mt-5 gap-5">
                <div class="flex gap-1 justify-center items-center">
                    <label for="txtNombre" 
                    class="text-default-800 text-sm font-medium inline-block mb-2">Nombre</label>
                    <input type="text" id="txtNombre" class="form-input" style="width: 350px">
                </div>
                <button type="button" id="btnRegistrar" disabled
                class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                    Registrar Legajo <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </div>

</div>

@endsection

@vite(['resources/js/functions/legajo.js'])
@section('script')

@endsection