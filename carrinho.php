<?php
session_start();

function processarAcaoCarrinho() {
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
        header("Location: carrinho.php");
        exit();
    }
    
    if(isset($_POST['aumentar']) || isset($_POST['diminuir'])) {
        $key = $_POST['key'];
        
        if(isset($_SESSION['carrinho'][$key])) {
            if(isset($_POST['aumentar'])) {
                $_SESSION['carrinho'][$key]['quantidade']++;
                $_SESSION['msg_sucesso'] = "📈 Quantidade aumentada!";
            } elseif(isset($_POST['diminuir'])) {
                if($_SESSION['carrinho'][$key]['quantidade'] > 1) {
                    $_SESSION['carrinho'][$key]['quantidade']--;
                    $_SESSION['msg_sucesso'] = "📉 Quantidade diminuída!";
                } else {
                    unset($_SESSION['carrinho'][$key]);
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                    $_SESSION['msg_sucesso'] = "📚 Item removido do carrinho!";
                }
            }
        } else {
            $_SESSION['msg_erro'] = "❌ Erro: Item não encontrado no carrinho!";
        }
        header("Location: carrinho.php");
        exit();
    }
    
    if(isset($_POST['remover'])) {
        $key = $_POST['key'];
        
        if(isset($_SESSION['carrinho'][$key])) {
            $titulo_removido = $_SESSION['carrinho'][$key]['titulo'];
            unset($_SESSION['carrinho'][$key]);
            $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
            $_SESSION['msg_sucesso'] = "📚 \"" . htmlspecialchars($titulo_removido) . "\" foi removido do carrinho!";
        } else {
            $_SESSION['msg_erro'] = "❌ Erro: Item não encontrado no carrinho!";
        }
        header("Location: carrinho.php");
        exit();
    }
    
    if(isset($_POST['limpar'])) {
        if(isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
            $total_itens = count($_SESSION['carrinho']);
            unset($_SESSION['carrinho']);
            $_SESSION['msg_sucesso'] = "🗑️ Carrinho limpo com sucesso! " . $total_itens . " item(s) foram removidos.";
        } else {
            $_SESSION['msg_erro'] = "❌ O carrinho já estava vazio!";
        }
        header("Location: carrinho.php");
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    processarAcaoCarrinho();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras - Entre Linhas</title>
    <link rel="stylesheet" href="stylecarrinho.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    if(isset($_SESSION['msg_sucesso'])) {
        echo '<div class="mensagem-sucesso">'.$_SESSION['msg_sucesso'].'</div>';
        unset($_SESSION['msg_sucesso']);
    }
    
    if(isset($_SESSION['msg_erro'])) {
        echo '<div class="mensagem-erro">'.$_SESSION['msg_erro'].'</div>';
        unset($_SESSION['msg_erro']);
    }
    ?>
    
    <div class="header">
        <div class="logo">
            <img src="logolivraria.png" width="200" height="120" alt="Logo Entre Linhas">
        </div>
        
        <div class="nav-links">
            <a href="home.php" class="home-link">🏠 Voltar à Loja</a>
            <a href="loginusuario.html" class="login-link">
                <img src="loginicon.png" width="80" height="50" alt="Login">
            </a>
        </div>
    </div>
    
    <div class="container">
        <h1 class="main-title">🛒 Seu Carrinho de Compras</h1>
        
        <?php
        if(!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
            echo '<div class="carrinho-vazio">';
            echo '<div class="icone-carrinho">🛒</div>';
            echo '<h2>Seu carrinho está vazio</h2>';
            echo '<p>Que tal adicionar alguns livros incríveis?</p>';
            echo '<a href="home.php" class="btn-continuar">📚 Continuar Comprando</a>';
            echo '</div>';
        } else {
            $total_geral = 0;
            $total_itens = 0;
            
            echo '<div class="carrinho-header">';
            echo '<div class="col-produto">Produto</div>';
            echo '<div class="col-preco">Preço Unit.</div>';
            echo '<div class="col-quantidade">Quantidade</div>';
            echo '<div class="col-total">Total</div>';
            echo '<div class="col-acoes">Ações</div>';
            echo '</div>';
            
            echo '<div class="carrinho-itens">';
            
            foreach($_SESSION['carrinho'] as $key => $item) {
                $subtotal = $item['preco'] * $item['quantidade'];
                $total_geral += $subtotal;
                $total_itens += $item['quantidade'];
                
                echo '<div class="item-carrinho">';
                
                echo '<div class="col-produto">';
                echo '<div class="produto-info">';
                if(!empty($item['foto1'])) {
                    echo '<img src="fotos/'.htmlspecialchars($item['foto1']).'" alt="Capa do livro" class="produto-imagem">';
                } else {
                    echo '<div class="sem-capa-mini">📚</div>';
                }
                echo '<div class="produto-detalhes">';
                echo '<h3>'.htmlspecialchars($item['titulo']).'</h3>';
                echo '<p class="autor">📝 '.htmlspecialchars($item['autor']).'</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="col-preco">';
                echo '<span class="preco">R$ '.number_format($item['preco'], 2, ',', '.').'</span>';
                echo '</div>';
                
                echo '<div class="col-quantidade">';
                echo '<form method="post" class="form-quantidade">';
                echo '<input type="hidden" name="key" value="'.$key.'">';
                echo '<button type="submit" name="diminuir" class="btn-quantidade">-</button>';
                echo '<span class="quantidade">'.$item['quantidade'].'</span>';
                echo '<button type="submit" name="aumentar" class="btn-quantidade">+</button>';
                echo '</form>';
                echo '</div>';
                
                echo '<div class="col-total">';
                echo '<span class="subtotal">R$ '.number_format($subtotal, 2, ',', '.').'</span>';
                echo '</div>';
                
                echo '<div class="col-acoes">';
                echo '<form method="post">';
                echo '<input type="hidden" name="key" value="'.$key.'">';
                echo '<button type="submit" name="remover" class="btn-remover" onclick="return confirm(\'Tem certeza que deseja remover este item do carrinho?\')">🗑️</button>';
                echo '</form>';
                echo '</div>';
                
                echo '</div>';
            }
            
            echo '</div>';
            
            echo '<div class="carrinho-resumo">';
            echo '<div class="resumo-box">';
            echo '<h3>📋 Resumo do Pedido</h3>';
            echo '<div class="resumo-linha">';
            echo '<span>Total de itens:</span>';
            echo '<span class="valor">'.$total_itens.' livro(s)</span>';
            echo '</div>';
            echo '<div class="resumo-linha total">';
            echo '<span>Total Geral:</span>';
            echo '<span class="valor-total">R$ '.number_format($total_geral, 2, ',', '.').'</span>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="acoes-carrinho">';
            echo '<a href="home.php" class="btn-continuar">📚 Continuar Comprando</a>';
            echo '<form method="post" style="display: inline;">';
            echo '<button type="submit" name="limpar" class="btn-limpar" onclick="return confirm(\'Tem certeza que deseja esvaziar o carrinho?\')">🗑️ Limpar Carrinho</button>';
            echo '</form>';
            echo '<button class="btn-finalizar" onclick="finalizarCompra()">💳 Finalizar Compra</button>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    
    <footer class="footer">
        <p>&copy; 2025 Entre Linhas - Sua Livraria Online. Todos os direitos reservados.</p>
    </footer>
    
    <script>
        function finalizarCompra() {
            alert('Compra Finalizada!');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const itens = document.querySelectorAll('.item-carrinho');
            itens.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
