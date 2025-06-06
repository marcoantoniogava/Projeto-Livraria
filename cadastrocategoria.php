<?php
$conectar = mysqli_connect('localhost', 'root', '', 'livraria');

if (mysqli_connect_errno()) {
    echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();
    exit();
}

if (isset($_POST['Gravar'])) {
    $nome = $_POST['nome'];
    $codcategoria = $_POST['codcategoria'];

    $nome = mysqli_real_escape_string($conectar, $nome);
    $codcategoria = mysqli_real_escape_string($conectar, $codcategoria);

    $sql = "SELECT * FROM categoria WHERE codcategoria = '$codcategoria'";
    $resultado = mysqli_query($conectar, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        echo "Este código de categoria já está cadastrado!";
    } else {
        $sql = "INSERT INTO categoria (codcategoria, nome) VALUES ('$codcategoria', '$nome')";
        if (mysqli_query($conectar, $sql)) {
            echo "Dados cadastrados com sucesso!";
        } else {
            echo "Erro ao gravar os dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Alterar'])) {
    $nome = $_POST['nome'];
    $codcategoria = $_POST['codcategoria'];

    $nome = mysqli_real_escape_string($conectar, $nome);
    $codcategoria = mysqli_real_escape_string($conectar, $codcategoria);

    $sql_verifica = "SELECT codcategoria FROM categoria WHERE codcategoria = '$codcategoria'";
    $resultado_verifica = mysqli_query($conectar, $sql_verifica);

    if (mysqli_num_rows($resultado_verifica) == 0) {
        echo "Erro ao alterar dados, o codigo nao existe!";
    } else {
        $sql = "UPDATE categoria SET nome = '$nome' WHERE codcategoria = '$codcategoria'";
        
        $resultado = mysqli_query($conectar, $sql);
        
        if ($resultado == TRUE) {
            echo "Dados alterados com sucesso!";
        } else {
            echo "Erro ao alterar dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Excluir'])) {
    $codcategoria = $_POST['codcategoria'];

    $codcategoria = mysqli_real_escape_string($conectar, $codcategoria);

    $sql_verifica = "SELECT codcategoria FROM categoria WHERE codcategoria = '$codcategoria'";
    $resultado_verifica = mysqli_query($conectar, $sql_verifica);

    if (mysqli_num_rows($resultado_verifica) == 0) {
        echo "Erro ao excluir dados, o codigo nao existe!";
    } else {
        $sql = "DELETE FROM categoria WHERE codcategoria = '$codcategoria'";
        
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado == TRUE) {
            echo "Dados excluidos com sucesso!";
        } else {
            echo "Erro ao excluir dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Pesquisar'])) {
    $sql = "SELECT * FROM categoria";

    $resultado = mysqli_query($conectar, $sql);

    if (mysqli_num_rows($resultado) == 0) {
        echo "Erro ao encontrar dados! (Provavelmente não existem)";
    } else {
        echo "<b>"."Resultado da Pesquisa por categoria: "."</b><br><br>";
        while ($dados = mysqli_fetch_array($resultado)) {
            echo "//////////////////////////////<br>";
            echo "Codigo da categoria: {$dados['codcategoria']}<br>";
            echo "Nome: {$dados['nome']}<br>";
        }
    }
}

mysqli_close($conectar);
?>