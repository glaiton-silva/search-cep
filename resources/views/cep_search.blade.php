<!DOCTYPE html>
<html>
<head>
    <title>Consulta CEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .container {
            margin-top: 20px;
        }
        .form-container, .results-container {
            width: 48%;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .modal {
            justify-content: center;
            align-items: center;
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 20px;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 600px;
            text-align: center;
            position: relative;
        }
        .modal-close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
        }
        .modal-close:hover,
        .modal-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Consulta CEP</a>
            </div>
        </nav>
    </header>
    <div class="container">
        <div class="row">
            <div class="col-md-6 form-container">
                <h2>Consulta de CEPs</h2>
                <form id="cepForm">
                    <div class="mb-3">
                        <label for="ceps" class="form-label">Digite os CEPs separados por vírgula:</label>
                        <input type="text" class="form-control" id="ceps" name="ceps">
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitButton">Buscar</button>
                </form>
            </div>
            <div class="col-md-6 results-container" id="resultsContainer" style="display: none;">
                <h3>Resultados:</h3>
                <div id="results" class="list-group"></div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Consulta CEP. Todos os direitos reservados.</p>
    </footer>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <p id="messageContent"></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script>
        document.getElementById('cepForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const ceps = document.getElementById('ceps').value;
            const resultsContainer = document.getElementById('resultsContainer');
            const resultsElement = document.getElementById('results');
            const messageModal = document.getElementById('messageModal');
            const messageContent = document.getElementById('messageContent');
            const submitButton = document.getElementById('submitButton');

            // Disable button and change text
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';

            resultsElement.innerHTML = '';
            resultsContainer.style.display = 'none';

            fetch(`/search/local/${ceps}`)
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        // Re-enable button and restore text
                        submitButton.disabled = false;
                        submitButton.textContent = 'Buscar';

                        if (data.length > 0) {
                            resultsContainer.style.display = 'block';
                            data.forEach(result => {
                                const card = document.createElement('div');
                                card.classList.add('card', 'mb-3');

                                const cardBody = document.createElement('div');
                                cardBody.classList.add('card-body');

                                cardBody.innerHTML = `
                                    <h5 class="card-title">${result.label}</h5>
                                    <p class="card-text"><strong>CEP:</strong> ${result.cep}</p>
                                    <p class="card-text"><strong>Logradouro:</strong> ${result.logradouro}</p>
                                    <p class="card-text"><strong>Complemento:</strong> ${result.complemento}</p>
                                    <p class="card-text"><strong>Bairro:</strong> ${result.bairro}</p>
                                    <p class="card-text"><strong>Localidade:</strong> ${result.localidade}</p>
                                    <p class="card-text"><strong>UF:</strong> ${result.uf}</p>
                                    <p class="card-text"><strong>IBGE:</strong> ${result.ibge}</p>
                                    <p class="card-text"><strong>GIA:</strong> ${result.gia}</p>
                                    <p class="card-text"><strong>DDD:</strong> ${result.ddd}</p>
                                    <p class="card-text"><strong>SIAFI:</strong> ${result.siafi}</p>
                                `;

                                card.appendChild(cardBody);
                                resultsElement.appendChild(card);
                            });
                            showMessage('Busca concluída com sucesso!', 'success');
                        } else {
                            showMessage('Nenhum resultado encontrado.', 'error');
                        }
                    }, 1000); // Ensure button is disabled for at least 1 second
                })
                .catch(error => {
                    console.error('Erro na busca:', error);
                    // Re-enable button and restore text
                    submitButton.disabled = false;
                    submitButton.textContent = 'Buscar';
                    showMessage('Erro ao buscar os CEPs.', 'error');
                });
        });

        function showMessage(message, type) {
            const messageModal = document.getElementById('messageModal');
            const messageContent = document.getElementById('messageContent');
            messageContent.textContent = message;
            messageModal.style.display = 'flex';

            if (type === 'success') {
                messageContent.style.color = 'green';
            } else {
                messageContent.style.color = 'red';
            }

            setTimeout(() => {
                messageModal.style.display = 'none';
            }, 5000); // Hide the modal after 5 seconds
        }

        // Close the modal when the user clicks on <span> (x)
        document.querySelector('.modal-close').onclick = function() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Close the modal when the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            if (event.target == document.getElementById('messageModal')) {
                document.getElementById('messageModal').style.display = 'none';
            }
        }
    </script>
</body>
</html>
