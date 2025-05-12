<?php
//conectar com banco de dados
$conectar = mysql_connect('localhost','root','');
$banco = mysql_select_db('livraria');

if (isset($_POST['Gravar']))
{
//receber as variaveis do HTML
    $nome = $_POST['nome'];
    $codeditora = $_POST['codeditora'];

    $sql = "SELECT * FROM editora WHERE codeditora = '$codeditora'";
    
    $resultado = mysql_query($sql, $conectar);

    if (mysql_num_rows($resultado) > 0)
    {
        echo "Este nome ja esta cadastrado!";
    }
    else 
    {
        $sql = "INSERT INTO editora (codeditora, nome) VALUES ('$codeditora', '$nome')";
        if (mysql_query($sql, $conectar)) {
            echo "Dados cadastrados com sucesso!";
        }
        else {
            echo "Erro ao gravar os dados!";
        }
    }
}

else if (isset($_POST['Alterar']))
{
//receber as variaveis do HTML
    $nome = $_POST['nome'];
    $codeditora = $_POST['codeditora'];

    $sql_verifica = "SELECT codeditora FROM editora WHERE codeditora = '$codeditora'";
    $resultado_verifica = mysql_query($sql_verifica, $conectar);

    if (mysql_num_rows($resultado_verifica) == 0) {
        echo "Erro ao alterar dados, o codigo nao existe!";
    } 
    
    else {
            $sql = "UPDATE editora SET nome = '$nome' WHERE codeditora = '$codeditora'";
            
            $resultado = mysql_query($sql, $conectar);
            
            if ($resultado == TRUE)
            {
                echo "Dados alterados com sucesso!";
            }
            
            else
            {
                echo "Erro ao alterar dados!";
            }
        }
}

else if (isset($_POST['Excluir']))
{
//receber as variaveis do HTML
    $codeditora = $_POST['codeditora'];

    $sql_verifica = "SELECT codeditora FROM editora WHERE codeditora = '$codeditora'";
    $resultado_verifica = mysql_query($sql_verifica, $conectar);

    if (mysql_num_rows($resultado_verifica) == 0) {
        echo "Erro ao excluir dados, o codigo nao existe!";
    }

    else {
        $sql = "DELETE FROM editora WHERE codeditora = '$codeditora'";
        
        $resultado = mysql_query($sql);

        if ($resultado == TRUE)
        {
            echo "Dados excluidos com sucesso!";
        }
        else
        {
            echo "Erro ao excluir dados!";
        }
    }
}

else if (isset($_POST['Pesquisar']))
{
    $sql = "SELECT * FROM editora";

    $resultado = mysql_query($sql);

    if (mysql_num_rows($resultado) == 0)
    {
        echo "Erro ao encontrar dados! (Provavelmente não existem)";
    }
    else
    {
        echo "<b>"."Resultado da Pesquisa por editora: "."</b><br><br>";
        while ($dados = mysql_fetch_array($resultado))
        {
            echo "//////////////////////////////<br>";
            echo "Codigo da editora: $dados[codeditora]<br>";
            echo "Nome: $dados[nome]<br>";
        }
    }
}

?>

//TRANSFERIR PARA MYSQLI