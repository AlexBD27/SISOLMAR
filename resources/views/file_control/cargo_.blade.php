@extends('layouts.vertical', ['title' => 'Gestión de Cargos'])

@section('css')

@endsection

@section('content')

@include("layouts.shared/page-title", ["subtitle" => "Recursos Humanos", "title" => "Cargos"])
<script src="https://kit.fontawesome.com/76256ea07c.js" crossorigin="anonymous"></script>

<div id="modal-editar-cargo"
    class="hs-overlay w-full h-full fixed top-0 left-0 z-70 transition-all duration-500 overflow-x-hidden overflow-y-auto hidden pointer-events-none">
    <div
        class="-translate-y-5 hs-overlay-open:translate-y-0 hs-overlay-open:opacity-100 opacity-0 ease-in-out transition-all duration-500 sm:max-w-lg sm:w-full my-8 sm:mx-auto flex flex-col bg-white shadow-sm rounded">
        <div class="flex flex-col border border-default-200 shadow-sm rounded-lg  pointer-events-auto">
            <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                <h3 class="text-lg font-medium text-default-900">
                    Agregar Cargo
                </h3>
                <button type="button" class="cerrar-modal text-default-600 cursor-pointer"
                    data-hs-overlay="#modal-agregar-cargo">
                    <i class="i-tabler-x text-lg"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <label for="txtNombreCargo">Nombre del cargo: </label>
                <input type="text" placeholder="" id="txtNombreCargo"
                    class="w-80 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm">

                {{-- <button type="button" class="btn bg-primary text-white rounded-full mx-auto" 
                  data-hs-overlay="#modal-agregar-cargo">
                      Registrar
                  </button> --}}

                {{-- <p class="mt-1 text-default-600">
                      This is a wider card with supporting text below as a natural lead-in
                      to
                      additional content.
                  </p> --}}
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-default-200">
                <button type="button" class="cerrar-modal btn bg-secondary text-white"
                    data-hs-overlay="#modal-agregar-cargo">
                    <i class="i-tabler-x me-1"></i>
                    Cerrar
                </button>
                <a class="btn bg-primary text-white" href="#">
                    Registrar
                </a>
            </div>

        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-8">

    <div class="card">
        
        <div class="card-header">
            <h3 class="text-center card-title">REGISTRO DE CARGOS</h3>
        </div>
        <div class="w-full px-5 py-2 mt-3 ">
            <input type="text" id="buscarCargo" placeholder="Buscar..."
                class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm" />
            <div id="tblCargos" class="w-full mt-8"></div>
        </div>
    </div> <!-- end card -->


    <div class="card">
     

        <div class="card-header">
            <h3 class="text-center card-title">GESTION DE CARGOS</h3>
        </div>

        <div class="p-6">
            <form id="formSaveCargo">
                <div class="grid lg:grid-cols-1 gap-6">
                    <div class="flex items-center justify-center gap-2">
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" name="rdTipoCargo" id="opOperativo" checked="">
                            <label class="ms-1.5" for="opOperativo">Operativo</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" name="rdTipoCargo" id="opAdmins">
                            <label class="ms-1.5" for="opAdmins">Administrativo</label>
                        </div>
                    </div>
                    <div>
                        <label for="slcArea" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">
                        Area </label>
                        <select class="form-select" id="slcArea">
                            
                        </select>
                    </div>
                    <div>
                        <label for="txtDescripcion" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">Descripción</label>
                        <input type="text" id="txtDescripcion" class="form-input">
                    </div>
                    <div>
                        <label for="txtAbreviatura" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">Abreviatura</label>
                        <input type="text" id="txtAbreviatura" class="form-input">
                    </div>
                    <div>
                        <label for="slcPosicion" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">
                        Posición </label>
                        <select class="form-select" id="slcPosicion">
                            
                        </select>
                    </div>
                    <div>
                        <label for="slcGrupo" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">
                        Grupo </label>
                        <select class="form-select" id="slcGrupo">
                            
                        </select>
                    </div>

                    <div class="flex items-center justify-center gap-2">
                        <label>Vigencia</label>
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" name="rdTipoCargo" id="opOperativo" checked="">
                            <label class="ms-1.5" for="opOperativo">si</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" name="rdTipoCargo" id="opAdmins">
                            <label class="ms-1.5" for="opAdmins">no</label>
                        </div>
                    </div>

                    <div>
                        <label for="txtLabor" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">Labor Esp.</label>
                        <input type="text" id="txtLabor" class="form-input">
                    </div>


                    <div>
                        <label for="nombre"
                            class="text-default-800 text-sm font-medium inline-block mb-2">Nombre</label>
                        <input type="text" id="nombre" class="form-input" placeholder="Nombre del Cargo" required>
                    </div>

                    <div class="flex justify-center w-full mt-5">
                        <button type="submit"
                            class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white">Registrar Cargo <i class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- end card -->

    
</div>

@endsection

@vite(['resources/js/functions/cargo.js'])
@section('script')

@endsection