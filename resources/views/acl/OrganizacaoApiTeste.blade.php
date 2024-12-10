@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
        <div class="m-0 text-dark col-sm-6">
        <h1 class="m-0 text-dark"></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item ">Administração</li>
                <li class="breadcrumb-item active">Organizações</li>
            </ol>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>    
@stop

@section('content')

    <!-- datatables-perfils de Dados -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <!--área de título da Entidade-->
                        <div class="col-md-4 text-left h5"><b>Administração de Organizações</b></div>
                        <!--área de mensagens-->
                        <div class="col-md-5 text-left">
                            <div style="padding: 0px;  background-color: transparent;">
                                <div id="alert" class="alert alert-danger" style="margin-bottom: 0px; display: none; padding: 2px 5px 2px 5px;">
                                    <a class="close" onClick="$('.alert').hide()">&times;</a>  
                                    <div class="alert-content">Mensagem</div>
                                </div>
                            </div>                         
                        </div>
                        <!--área de botões-->
                        <div class="col-md-3 text-right">
                            <button id="btnRefresh" class="btn btn-default btn-sm btnRefresh" data-toggle="tooltip" title="Atualizar a tabela (Alt+R)">Refresh</button>
                            @can('is_admin')
                            <button id="btnNovo" class="btnInserirNovo btn btn-success btn-sm" data-toggle="tooltip" title="Adicionar um novo registro (Alt+N)" >Inserir Novo</button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Verificar se existem veículos -->
                    @if($veiculos)
                        <ul>
                            <!-- Loop para iterar sobre os veículos -->
                            @foreach($veiculos as $veiculo)
                                <li>{{ $veiculo->id }} {{ $veiculo->descricao }}</li> <!-- Exibir o nome do veículo -->
                            @endforeach
                        </ul>
                    @else
                        <p>Não há veículos cadastrados.</p>
                    @endif

                    <!-- compact | stripe | order-column | hover | cell-border | row-border | table-dark-->
                    <table id="datatables-perfils" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                        <thead></thead>
                        <tbody></tbody>
                        <tfoot></tfoot>                
                    </table>                 
                </div>
            </div>
        </div>
    </div>

    <h1>Chamada para API com Fetch</h1>
    <div id="result"></div>    

    <script type="text/javascript">

        $(document).ready(function () {

            const apiUrl = 'http://localhost:10000/api/veiculo';

            $('#btnRefresh').on("click", function (e) {
                e.stopImmediatePropagation();

                const formDados = {
                    "descricao": "Meriva prata do Renan 2 lugares",
                    "tipo": "Automóvel",
                    "marca_modelo": "Chevrolet/Meriva GL 1.6 Preto",
                    "capacidade": 2,
                    "motorista": "RENAN MARTINS",
                    "telefone": "(61) 98000-1111",
                    "observacao": "Compacto com bom bagageiro para 2 passageiros", 
                    "ativo": "SIM"
                } 

                // AXIOS - Fazendo a requisição com 
                // axios.put("http://localhost:10000/api/veiculo/15", formDados, {
                // axios.get("http://localhost:10000/api/veiculo", {
                axios.get('https://apieventos.voluntary.com.br/api/veiculo', {
                    headers: {
                        'Content-Type': 'application/json',  
                        'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                    }
                }).then(response => {
                    document.getElementById('result').innerHTML = JSON.stringify(response.data, null, 2);
                }).catch(error => {
                    if (error.response) {
                        // A requisição foi feita e o servidor respondeu com um código de status que não está na faixa 2xx
                        console.error('Erro na requisição:', error.response.data);
                        console.error('Status HTTP:', error.response.status);
                        
                        if (error.response.status === 401) {
                            console.log('Não autorizado! Verifique o token.');
                        } else if (error.response.status === 500) {
                            console.log('Erro no servidor!');
                        }
                    } else if (error.request) {
                        // A requisição foi feita, mas não houve resposta
                        console.error('Erro na resposta:', error.request);
                        throw new Error('Erro na resposta');
                    } else {
                        // Algo aconteceu ao configurar a requisição
                        console.error('Erro desconhecido:', error.message);
                        throw new Error('Erro desconhecido');
                    }                    
                });                    

                // AJAX - Fazendo a requisição com 
                // $.ajax({
                //     url: apiUrl,
                //     method: 'GET',                              // método HTTP (GET, POST, PUT, DELETE, etc.)
                //     headers: {
                //         'contentType': 'application/json',      // Tipo de conteúdo de envio (JSON)
                //         'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                //     },
                //     data: JSON.stringify(formDados),            // para transformar os dados em uma string JSON antes de enviar.
                //     success: function(response) {
                //         // console.log(response);
                //     },
                //     error: function(xhr, status, error) {
                //         console.log(xhr.status, xhr.statusText );
                //     }
                // });
                
                // $.ajax({
                //     url: 'http://localhost:10000/api/veiculo/1',
                //     method: 'GET',
                //     headers: {
                //         'contentType': 'application/json',
                //         'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                //     },
                //     success: function(response) {
                //         // console.log(response);
                //     },
                //     error: function(xhr, status, error) {
                //         console.log(xhr.status, xhr.statusText );
                //     }
                // });

                // FETCH - Fazendo a requisição com 
                // fetch(apiUrl, {
                //     method: 'GET',                                  // Método POST
                //     headers: {
                //         'Content-Type': 'application/json',  
                //         'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                //     },
                //     // body: JSON.stringify(formDados)  // Dados que serão enviados no corpo da requisição
                // }).then(response => {
                //     console.log(response);

                //     if (! response.ok) {
                //         throw new Error('Erro na requisição');
                //     }
                //     return response.json(); // Parseia a resposta como JSON
                // }).then(data => {
                //     // Exibe os dados na tela
                //     document.getElementById('result').innerHTML = JSON.stringify(data, null, 2);
                // }).catch(error => {
                //     // Exibe o erro, caso algo dê errado
                //     document.getElementById('result').innerHTML = 'Erro: ' + error.message;
                // });

                // $('#datatables-entidades').DataTable().ajax.reload(null, false);
            });               

        });


    </script>    

@stop
