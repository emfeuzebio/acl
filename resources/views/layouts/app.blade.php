@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}
@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

@section('content')
    @yield('content_body')
    <script type="text/javascript" language="javascript" class="init">

    $(document).ready(function() {
        // traduz todos DataTables
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}"
            }
        });   
    });   
    </script>
@stop

{{-- Add common Javascript/Jquery code --}}
@push('js')
<script type="text/javascript" language="javascript" class="init">

    // EUZ javascript configurações globais
    $(document).ready(function() {

        //ativa o tooltip nas páginas
        $('body').tooltip({ selector: '[data-toggle="tooltip"]'});

        //controla a exibição do Loading dos Ajax para todas as páginas
        $(document).on({
            ajaxStart: function () {
                $("#datatables_processing").show(); // ID que puxa o loading do datatable
            },
            ajaxStop: function () {
                $("#datatables_processing").hide(); // ID que puxa o loading do datatable
            }            
        });   

        // configura os Modais para terem seu conteúdo limpo ao serem fechados (hide)
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
            $('.invalid-feedback').text('').hide();
            // alert('Fechou Modal');
        });

    });

</script>
@endpush

{{-- Create a common footer --}}
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop


{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    /*
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush