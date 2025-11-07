<?php
$servername = "localhost";
$username = "root";
$password = "Senai@118";
$dbname = "teste_conexao";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Criar tabela de filmes, se não existir
$sql = "CREATE TABLE IF NOT EXISTS filmes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    diretor VARCHAR(255),
    genero VARCHAR(100),
    ano INT
)";
$conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Cadastro de Filmes</title>
</head>
<body>

<h1> Cadastro de Filmes</h1>

<?php
// Exibir formulário
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo '
    <form method="POST">
        <label>Título do Filme:</label>
        <input type="text" name="titulo" required><br>

        <label>Diretor:</label>
        <input type="text" name="diretor" required><br>

        <label>Gênero:</label>
        <input type="text" name="genero" required><br>

        <label>Ano:</label>
        <input type="number" name="ano" required><br>

        <input type="submit" value="Adicionar Filme">
    </form>';
}

// Receber e inserir dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $diretor = $_POST["diretor"];
    $genero = $_POST["genero"];
    $ano = $_POST["ano"];

    if ($titulo == "" || $diretor == "" || $genero == "" || $ano == "") {
        echo "<p style='color:red;'>Preencha todos os campos corretamente.</p>";
    } else {
        // Inserir dados no banco
        $sqlinsert = "INSERT INTO filmes (titulo, diretor, genero, ano)
                      VALUES ('$titulo', '$diretor', '$genero', '$ano')";
        if ($conn->query($sqlinsert) === TRUE) {
            echo "<p style='color:green;'>Filme inserido com sucesso!</p>";

            // Mostrar o último registro inserido
            $ultimo_id = $conn->insert_id;
            $sqlSelect = "SELECT * FROM filmes WHERE id = $ultimo_id";
            $resultado = $conn->query($sqlSelect);
            $filme = $resultado->fetch_assoc();

            echo "<p>ID: " . $filme['id'] . " | " .
                 "Título: " . $filme['titulo'] . " | " .
                 "Diretor: " . $filme['diretor'] . " | " .
                 "Gênero: " . $filme['genero'] . " | " .
                 "Ano: " . $filme['ano'] . "</p>";
        } else {
            echo "<p style='color:red;'>Erro ao inserir: " . $conn->error . "</p>";
        }
    }
}

// Listar todos os filmes
echo "<h3>🎥 Filmes Cadastrados</h3>";
$sqlALL = "SELECT * FROM filmes";
$result = $conn->query($sqlALL);

if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Diretor</th>
        <th>Gênero</th>
        <th>Ano</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["titulo"] . "</td>
                <td>" . $row["diretor"] . "</td>
                <td>" . $row["genero"] . "</td>
                <td>" . $row["ano"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum filme cadastrado.</p>";
}

// Mostrar filmes ordenados por título
echo "<h3> Filmes Ordenados por Título (A-Z)</h3>";
$sqlOrder = "SELECT * FROM filmes ORDER BY titulo ASC";
$resOrder = $conn->query($sqlOrder);

if ($resOrder->num_rows > 0) {
    echo "<table border='1'>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Diretor</th>
        <th>Gênero</th>
        <th>Ano</th>
    </tr>";
    while ($row = $resOrder->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["titulo"] . "</td>
                <td>" . $row["diretor"] . "</td>
                <td>" . $row["genero"] . "</td>
                <td>" . $row["ano"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum filme cadastrado.</p>";
}

// Contar total de filmes
$sqlCount = "SELECT COUNT(*) AS total FROM filmes";
$resCount = $conn->query($sqlCount);
$linhaCount = $resCount->fetch_assoc();
echo "<br><b>Total de filmes cadastrados:</b> " . $linhaCount['total'] . "<br>";

// Fechar conexão
$conn->close();
?>

</body>
</html>


                
                

