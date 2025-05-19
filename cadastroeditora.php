<?php
//conectar com banco de dados
$conectar = mysqli_connect('localhost', 'root', '', 'livraria');

// Verificar conexão
if (mysqli_connect_errno()) {
    echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();
    exit();
}

if (isset($_POST['Gravar'])) {
    //receber as variaveis do HTML
    $nome = $_POST['nome'];
    $codeditora = $_POST['codeditora'];

    // Sanitizar entradas para prevenir SQL Injection
    $nome = mysqli_real_escape_string($conectar, $nome);
    $codeditora = mysqli_real_escape_string($conectar, $codeditora);

    // Verificar se o código da editora já existe
    $sql = "SELECT * FROM editora WHERE codeditora = '$codeditora'";
    $resultado = mysqli_query($conectar, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        echo "Este código de editora já está cadastrado!";
    } else {
        // Se não existir, inserir os dados
        $sql = "INSERT INTO editora (codeditora, nome) VALUES ('$codeditora', '$nome')";
        if (mysqli_query($conectar, $sql)) {
            echo "Dados cadastrados com sucesso!";
        } else {
            echo "Erro ao gravar os dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Alterar'])) {
    //receber as variaveis do HTML
    $nome = $_POST['nome'];
    $codeditora = $_POST['codeditora'];

    // Sanitizar entradas
    $nome = mysqli_real_escape_string($conectar, $nome);
    $codeditora = mysqli_real_escape_string($conectar, $codeditora);

    $sql_verifica = "SELECT codeditora FROM editora WHERE codeditora = '$codeditora'";
    $resultado_verifica = mysqli_query($conectar, $sql_verifica);

    if (mysqli_num_rows($resultado_verifica) == 0) {
        echo "Erro ao alterar dados, o codigo nao existe!";
    } else {
        $sql = "UPDATE editora SET nome = '$nome' WHERE codeditora = '$codeditora'";
        
        $resultado = mysqli_query($conectar, $sql);
        
        if ($resultado == TRUE) {
            echo "Dados alterados com sucesso!";
        } else {
            echo "Erro ao alterar dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Excluir'])) {
    //receber as variaveis do HTML
    $codeditora = $_POST['codeditora'];

    // Sanitizar entrada
    $codeditora = mysqli_real_escape_string($conectar, $codeditora);

    $sql_verifica = "SELECT codeditora FROM editora WHERE codeditora = '$codeditora'";
    $resultado_verifica = mysqli_query($conectar, $sql_verifica);

    if (mysqli_num_rows($resultado_verifica) == 0) {
        echo "Erro ao excluir dados, o codigo nao existe!";
    } else {
        $sql = "DELETE FROM editora WHERE codeditora = '$codeditora'";
        
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado == TRUE) {
            echo "Dados excluidos com sucesso!";
        } else {
            echo "Erro ao excluir dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Pesquisar'])) {
    $sql = "SELECT * FROM editora";

    $resultado = mysqli_query($conectar, $sql);

    if (mysqli_num_rows($resultado) == 0) {
        echo "Erro ao encontrar dados! (Provavelmente não existem)";
    } else {
        echo "<b>"."Resultado da Pesquisa por editora: "."</b><br><br>";
        while ($dados = mysqli_fetch_array($resultado)) {
            echo "//////////////////////////////<br>";
            echo "Codigo da editora: {$dados['codeditora']}<br>";
            echo "Nome: {$dados['nome']}<br>";
        }
    }
}

// Fechar conexão
mysqli_close($conectar);
?>