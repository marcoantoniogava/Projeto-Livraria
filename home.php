<?php
session_start();
$connect = new mysqli('localhost', 'root', '', 'livraria');

// Verificar conexão
if ($connect->connect_error) {
    die("Falha na conexão: " . $connect->connect_error);
}

// Definir charset
$connect->set_charset("utf8");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entre Linhas - Sua Livraria Online</title>
    <link rel="stylesheet" href="stylehome.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    // Exibir mensagem de sucesso se existir
    if(isset($_SESSION['msg_sucesso'])) {
        echo '<div class="mensagem-sucesso">'.$_SESSION['msg_sucesso'].'</div>';
        // Limpar a mensagem após exibir
        unset($_SESSION['msg_sucesso']);
    }
    ?>
    
    <div class="header">
        <div class="logo">
            <img src="logolivraria.png" width="200" height="120" alt="Logo Entre Linhas">
        </div>
        <div class="nav-links">
            <a href="carrinho.php" class="carrinho-link">
                🛒 Carrinho:
                <?php
                // Mostrar quantidade de itens no carrinho
                if(isset($_SESSION['carrinho'])) {
                    $total_itens = 0;
                    foreach($_SESSION['carrinho'] as $item) {
                        $total_itens += $item['quantidade'];
                    }
                    echo '<span class="carrinho-contador">'.$total_itens.'</span>';
                } else {
                    echo '<span class="carrinho-contador">0</span>';
                }
                ?>
            </a>
            <a href="loginusuario.html" class="login-link">
                <img src="loginicon.png" width="80" height="50" alt="Login">
            </a>
        </div>
    </div>
    
    <h1 class="main-title">📚 Entre Linhas - Sua Livraria Online</h1>
    
    <h2 class="search-title">Encontre seu próximo livro</h2>
    
    <form name="formulario" method="post" action="" class="search-form">
        <div class="form-group">
            <!------ pesquisar Categorias/Gêneros -------------->
            <div class="form-control">
                <label for="categoria">Gênero</label>
                <select name="categoria" id="categoria">
                    <option value="" selected="selected">Todos os gêneros...</option>
                    <?php
                    $query = $connect->query("SELECT codcategoria, nome FROM categoria ORDER BY nome");
                    if($query) {
                        while($categorias = $query->fetch_assoc()) {
                    ?>
                    <option value="<?php echo $categorias['codcategoria']?>">
                        <?php echo htmlspecialchars($categorias['nome']) ?>
                    </option>
                    <?php 
                        }
                    }
                    ?>
                </select>
            </div>
            
            <!------ pesquisar editoras -------------->
            <div class="form-control">
                <label for="editora">Editora</label>
                <select name="editora" id="editora">
                    <option value="" selected="selected">Todas as editoras...</option>
                    <?php
                    $query = $connect->query("SELECT codeditora, nome FROM editora ORDER BY nome");
                    if($query) {
                        while($editora = $query->fetch_assoc()) {
                    ?>
                    <option value="<?php echo $editora['codeditora']?>">
                        <?php echo htmlspecialchars($editora['nome']) ?>
                    </option>
                    <?php 
                        }
                    }
                    ?>
                </select>
            </div>
            
            <!------ pesquisar autores -------------->
            <div class="form-control">
                <label for="autor">Autor</label>
                <select name="autor" id="autor">
                    <option value="" selected="selected">Todos os autores...</option>
                    <?php
                    $query = $connect->query("SELECT codautor, nome FROM autor ORDER BY nome");
                    if($query) {
                        while($autor = $query->fetch_assoc()) {
                    ?>
                    <option value="<?php echo $autor['codautor']?>">
                        <?php echo htmlspecialchars($autor['nome']) ?>
                    </option>
                    <?php 
                        }
                    }
                    ?>
                </select>
            </div>

            <!------ pesquisar por título -------------->
            <div class="form-control">
                <label for="titulo">Título do Livro</label>
                <input type="text" name="titulo" id="titulo" placeholder="Digite o título do livro..." value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
            </div>
        </div>

        <div style="text-align: center;">
            <input type="submit" name="pesquisar" value="🔍 Pesquisar Livros" class="btn-search">
        </div>
    </form>

    <?php
    if (isset($_POST['pesquisar'])) {
        // Verifica que as opções foram selecionadas ou não
        $editora = (empty($_POST['editora'])) ? 'null' : intval($_POST['editora']);
        $categoria = (empty($_POST['categoria'])) ? 'null' : intval($_POST['categoria']);
        $autor = (empty($_POST['autor'])) ? 'null' : intval($_POST['autor']);
        $titulo = (empty($_POST['titulo'])) ? 'null' : $connect->real_escape_string(trim($_POST['titulo']));

        $sql_livros = "SELECT l.codlivro, l.titulo, l.ano, l.preco, l.foto1, l.foto2, l.resenha, l.nrpaginas,
                              a.nome as autor_nome, e.nome as editora_nome, c.nome as categoria_nome
                       FROM livro l
                       LEFT JOIN autor a ON l.codautor = a.codautor
                       LEFT JOIN editora e ON l.codeditora = e.codeditora
                       LEFT JOIN categoria c ON l.codcategoria = c.codcategoria
                       WHERE 1=1";
        
        // Adiciona condições de filtro quando necessário
        if ($editora != 'null') {
            $sql_livros .= " AND l.codeditora = $editora";
        }
        
        if ($categoria != 'null') {
            $sql_livros .= " AND l.codcategoria = $categoria";
        }
        
        if ($autor != 'null') {
            $sql_livros .= " AND l.codautor = $autor";
        }

        if ($titulo != 'null') {
            $sql_livros .= " AND l.titulo LIKE '%$titulo%'";
        }
        
        $sql_livros .= " ORDER BY l.titulo";
        
        // Executar a consulta
        $seleciona_livros = $connect->query($sql_livros);

        // Verificar se houve erro na consulta
        if (!$seleciona_livros) {
            echo '<div class="erro"><h2>Erro na consulta: ' . $connect->error . '</h2></div>';
            echo '<div class="debug"><p>SQL executado: ' . htmlspecialchars($sql_livros) . '</p></div>';
        } else {
            // Mostrar as informações dos livros
            if($seleciona_livros->num_rows == 0) {
            echo '<div class="no-results"><h2>📚 Desculpe, mas sua busca não retornou resultados...</h2><p>Tente ajustar seus filtros de pesquisa.</p></div>';
        } else {
            echo "<h2 class='search-title'>📖 Resultado da pesquisa de Livros (".$seleciona_livros->num_rows." encontrado(s))</h2>";
            echo "<div class='livros'>";
            
            while ($dados = $seleciona_livros->fetch_object()) {
                echo "<div class='livro'>";
                echo "<div class='livro-header'>";
                echo "<h3 class='titulo-livro'>".htmlspecialchars($dados->titulo)."</h3>";
                echo "</div>";
                
                echo "<div class='livro-content'>";
                
                // Foto1 do livro
                echo "<div class='capa-container'>";
                if (!empty($dados->foto1)) {
                    echo '<img src="fotos/'.htmlspecialchars($dados->foto1).'" height="200" width="150" alt="Capa do livro" class="capa-livro" />';
                } else {
                    echo '<div class="sem-capa">📚<br>Sem capa</div>';
                }
                echo "</div>";
                
                echo "<div class='info-livro'>";
                echo "<p><strong>📝 Autor:</strong> ".htmlspecialchars($dados->autor_nome ?: 'Não informado')."</p>";
                echo "<p><strong>🏢 Editora:</strong> ".htmlspecialchars($dados->editora_nome ?: 'Não informado')."</p>";
                echo "<p><strong>🏷️ Gênero:</strong> ".htmlspecialchars($dados->categoria_nome ?: 'Não informado')."</p>";
                echo "<p><strong>📅 Ano:</strong> ".($dados->ano ?: 'Não informado')."</p>";
                echo "<p><strong>📄 Páginas:</strong> ".($dados->nrpaginas ?: 'Não informado')."</p>";
                echo "<p class='preco'><strong>💰 Preço:</strong> R$ ".number_format($dados->preco, 2, ',', '.')."</p>";
                
                // Resenha (se existir)
                if (!empty($dados->resenha)) {
                    echo "<div class='sinopse'>";
                    echo "<p><strong>📋 Resenha:</strong></p>";
                    echo "<p class='texto-sinopse'>".htmlspecialchars(substr($dados->resenha, 0, 200)).(strlen($dados->resenha) > 200 ? '...' : '')."</p>";
                    echo "</div>";
                }
                echo "</div>";
                
                echo "</div>";
                
                // Foto2 adicional (se existir)
                if (!empty($dados->foto2)) {
                    echo "<div class='foto-adicional'>";
                    echo '<img src="fotos/'.htmlspecialchars($dados->foto2).'" height="120" width="90" alt="Foto adicional do livro" />';
                    echo "</div>";
                }
                
                // Botão de adicionar ao carrinho
                echo "<div class='livro-acoes'>";
                echo "<form method='post' action='addcarrinho.php'>";
                echo "<input type='hidden' name='codlivro' value='".$dados->codlivro."'>";
                echo "<input type='hidden' name='titulo' value='".htmlspecialchars($dados->titulo)."'>";
                echo "<input type='hidden' name='autor' value='".htmlspecialchars($dados->autor_nome)."'>";
                echo "<input type='hidden' name='preco' value='".$dados->preco."'>";
                echo "<input type='hidden' name='foto1' value='".htmlspecialchars($dados->foto1)."'>";
                echo "<button type='submit' name='adicionar' class='btn-carrinho'>🛒 Adicionar ao Carrinho</button>";
                echo "</form>";
                echo "</div>";
                
                echo "</div>";
            }
            
            echo "</div>";
        }
        }
    }
    
    $connect->close();
    ?>
    
    <footer class="footer">
        <p>&copy; 2025 Entre Linhas - Sua Livraria Online. Todos os direitos reservados.</p>
    </footer>
</body>
</html>