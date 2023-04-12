<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT-4 create Landing page</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        textarea {
            width: 100%;
            height: 200px;
            margin-bottom: 10px;
            padding: 10px;
        }

        iframe {
            width: 100%;
            height: 500px;
            border: none;
            margin-top: 10px;
        }

        #btn {
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        /* coloca o Fundo iframe escuro */
        #view-page {
            background-color: #000 !important;
        }


    </style>
</head>

<body>
    <main class="p-8">
        <div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Descrição da sua Landing page</span>
                    <span class="label-text-alt" id="n-caracteres">0/2000</span>
                </label>
                <textarea id="description" class="textarea textarea-bordered h-24" placeholder="Descreva sua Uma Landing page simples com fundo verde e um título azul Hello World ao centro." id="input"></textarea>
                <label class="label">
                    <span class="label-text-alt" id="msg-info"></span>
                </label>
            </div>
            <div class="my-2">
            <button id="btn-enviar" class="btn btn-primary text-white">Enviar</button>
            <!-- Botao de download -->
            <a id="btn-download" class="btn btn-success text-white" download="#" style="display: none;">Download</a>
            </div>
        </div>

        <iframe class="rounded" id="view-page" src="my-landing-page" style="display: none;"></iframe>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {

            $.fn.renderIframe = function(renderIframe) {
                const iframe = $('#view-page');
                iframe.attr('src', '../pages/' + renderIframe + '.html');
                iframe.show();
                // Atualiza o conteúdo do iframe
                try {
                    iframe.contents().find('body').html('');
                iframe.load(function() {
                    $(this).contents().find('body').css('background-color', '#000');
                });
                } catch (error) {}
            }

            // Funcao observadora do textarea
            $.fn.observerDescription = function(element) {
                const caracteres = element.val().length;
                $('#n-caracteres').text(`${caracteres}/${limite}`);
                // Verifica se o número de caracteres é maior que 2000
                if (caracteres > limite) {
                    $('#msg-info').text('O número máximo de caracteres foi atingido');
                    // Desabilita o botão de enviar
                    $('#btn-enviar').attr('disabled', true);
                    // Remove o texto digitado a partir do 2000º caractere
                    $(this).val($(this).val().substring(0, limite));
                } else {
                    $('#msg-info').text('');
                    // Habilita o botão de enviar
                    $('#btn-enviar').attr('disabled', false);
                }

                // Muda a cor do texto de limite de caracteres de acordo com a porcentagem de caracteres digitados, começando de verde até vermelho em 0% e 100% respectivamente
                if (caracteres > 0 && caracteres <= (limite * 0.25)) {
                    $('#n-caracteres').css('color', 'green');
                } else if (caracteres > (limite * 0.25) && caracteres <= (limite * 0.5)) {
                    $('#n-caracteres').css('color', 'yellow');
                } else if (caracteres > (limite * 0.5) && caracteres <= (limite * 0.75)) {
                    $('#n-caracteres').css('color', 'orange');
                } else if (caracteres > (limite * 0.75) && caracteres <= limite) {
                    $('#n-caracteres').css('color', 'red');
                }
            }

            // Contagem de caracteres
            const limite = 2000;
            const elementDescription = $('#description');
            $(elementDescription).val('Uma Landing page simples com fundo cinza e um título azul Hello World ao centro.');
            $(elementDescription).observerDescription($(this));
            $(elementDescription).keyup(function() {
                $(this).observerDescription($(this));
            });


            $('#btn-enviar').click(function() {
                const description = $('#description').val();
                // Nome único do arquivo
                const fileName = Math.random().toString(36).substring(7);

                // Adiciona a mensagem de carregamento
                $('#msg-info').text('Criando sua Landing page, aguarde...');
                $('#msg-info').toggleClass('text-yellow-500');
                $(this).toggleClass('loading');
                $(this).attr('disabled', true);
                // Muda o texto do botão enviar para "Criando..."
                $(this).text('Criando...');

                $.post('../requests/request.php', { // Inicia a requisição
                    request: {
                        description: description,
                        fileName: fileName
                    }
                }, function(data) {
                    // Atualiza a mensagem de carregamento
                    $('#msg-info').text('Sua Landing page foi criada com sucesso!');
                    // Define a cor da mensagem de carregamento
                    $('#msg-info').toggleClass('text-yellow-500 text-lime-500');

                    // Botão de download
                    $('#btn-download').show();
                    $('#btn-download').attr('href', `../pages/${fileName}.html`);

                    // Botão de enviar
                    $('#btn-enviar').text('Enviar');
                    $('#btn-enviar').toggleClass('loading');
                    $('#btn-enviar').attr('disabled', false);

                    // Atualiza o iframe com o resultado
                    $(this).renderIframe(fileName);
                    // Para o intervalo de tempo
                    clearInterval(intervalId);
                });

                // Inicia o intervalo de tempo
                var intervalId = setInterval(function() {
                    // Atualiza o iframe com o resultado
                    $(this).renderIframe(fileName);
                }, 5000);

            });
        });
    </script>
</body>

</html>