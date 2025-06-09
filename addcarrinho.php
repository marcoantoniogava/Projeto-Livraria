<?php
session_start();

if(isset($_POST['adicionar'])) {
    $codlivro = $_POST['codlivro'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $preco = $_POST['preco'];
    $foto1 = $_POST['foto1'];
    $quantidade = 1;
    
    if(!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = array();
    }
    
    $livro_existe = false;
    foreach($_SESSION['carrinho'] as $key => $item) {
        if($item['codlivro'] == $codlivro) {
            $_SESSION['carrinho'][$key]['quantidade']++;
            $livro_existe = true;
            break;
        }
    }
    
    if(!$livro_existe) {
        $_SESSION['carrinho'][] = array(
            'codlivro' => $codlivro,
            'titulo' => $titulo,
            'autor' => $autor,
            'preco' => $preco,
            'foto1' => $foto1,
            'quantidade' => $quantidade
        );
    }
    
    $_SESSION['msg_sucesso'] = "📚 Livro adicionado ao carrinho com sucesso!";
    header("Location: home.php");
    exit();
} else {
    header("Location: home.php");
    exit();
}
?>
