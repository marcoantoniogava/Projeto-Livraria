<?php
$conectar = mysqli_connect('localhost', 'root', '', 'livraria');

if (mysqli_connect_errno()) {
    echo "Falha ao conectar ao MySQL: " . mysqli_connect_error();
    exit();
}

if (isset($_POST['Gravar'])) {
    $nome = $_POST['nome'];
    $codautor = $_POST['codautor'];
    $pais = $_POST['pais'];

    $nome = mysqli_real_escape_string($conectar, $nome);
    $codautor = mysqli_real_escape_string($conectar, $codautor);
    $pais = mysqli_real_escape_string($conectar, $pais);

    $sql = "SELECT * FROM autor WHERE codautor = '$codautor'";
    $resultado = mysqli_query($conectar, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        echo "Este código de autor já está cadastrado!";
    } else {
        $sql = "INSERT INTO autor (codautor, nome, pais) VALUES ('$codautor', '$nome', '$pais')";
        if (mysqli_query($conectar, $sql)) {
            echo "Dados cadastrados com sucesso!";
        } else {
            echo "Erro ao gravar os dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Alterar'])) {
    $nome = $_POST['nome'];
    $codautor = $_POST['codautor'];
    $pais = $_POST['pais'];

    $nome = mysqli_real_escape_string($conectar, $nome);
    $codautor = mysqli_real_escape_string($conectar, $codautor);
    $pais = mysqli_real_escape_string($conectar, $pais);

    $sql_verifica = "SELECT codautor FROM autor WHERE codautor = '$codautor'";
    $resultado_verifica = mysqli_query($conectar, $sql_verifica);

    if (mysqli_num_rows($resultado_verifica) == 0) {
        echo "Erro ao alterar dados, o codigo nao existe!";
    } else {
        $sql = "UPDATE autor SET nome = '$nome', pais = '$pais' WHERE codautor = '$codautor'";
        
        $resultado = mysqli_query($conectar, $sql);
        
        if ($resultado == TRUE) {
            echo "Dados alterados com sucesso!";
        } else {
            echo "Erro ao alterar dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Excluir'])) {
    $codautor = $_POST['codautor'];

    $codautor = mysqli_real_escape_string($conectar, $codautor);

    $sql_verifica = "SELECT codautor FROM autor WHERE codautor = '$codautor'";
    $resultado_verifica = mysqli_query($conectar, $sql_verifica);

    if (mysqli_num_rows($resultado_verifica) == 0) {
        echo "Erro ao excluir dados, o codigo nao existe!";
    } else {
        $sql = "DELETE FROM autor WHERE codautor = '$codautor'";
        
        $resultado = mysqli_query($conectar, $sql);

        if ($resultado == TRUE) {
            echo "Dados excluidos com sucesso!";
        } else {
            echo "Erro ao excluir dados: " . mysqli_error($conectar);
        }
    }
} else if (isset($_POST['Pesquisar'])) {
    $sql = "SELECT * FROM autor";

    $resultado = mysqli_query($conectar, $sql);

    if (mysqli_num_rows($resultado) == 0) {
        echo "Erro ao encontrar dados! (Provavelmente não existem)";
    } else {
        echo "<b>"."Resultado da Pesquisa por autor: "."</b><br><br>";
        while ($dados = mysqli_fetch_array($resultado)) {
            echo "//////////////////////////////<br>";
            echo "Codigo da autor: {$dados['codautor']}<br>";
            echo "Nome: {$dados['nome']}<br>";
            echo "País: {$dados['pais']}<br>";
        }
    }
}

mysqli_close($conectar);
?>
