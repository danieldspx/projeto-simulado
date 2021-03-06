<li class="menu-lista-item dropdown">
    <?php
        $notificacoes = DBSearch("notificacoes_dados","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"notif_ms");
        if(empty($notificacoes)){ //Se nao ha nada no DB, cadastra todas as notificacoes como nao lidas para esse user
            $notifExist = DBSearch("notificacoes");
            $mustSee = "";
            foreach($notifExist as $item){
                $mustSee .= $item['id']."-" ;
            }
            $dados['usuarios_id'] = $_SESSION['usuario']['id'];     
            $dados['notif_ms'] = $mustSee;
            DBCadastro("notificacoes_dados",$dados);
            $notificacoes[0]['notif_ms'] = $mustSee;
            $dados = $notifExist = array();
            unset($dados);
            unset($notifExist);
        }
        if($notificacoes[0]['notif_ms']!=""){
            $id_notif = explode("-",$notificacoes[0]['notif_ms']);
            $id_notif = array_filter($id_notif);
            $clauses = "id = ".implode(" OR id = ",$id_notif);
            $notif_conteudo = DBSearch("notificacoes","WHERE $clauses ORDER BY id DESC","conteudo,id"); //Busca as notificações pelo ID ($clauses)
            $quantidade = count($notif_conteudo);
            echo "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false' style='margin-left: -4px;color: #343;text-decoration: none;' onclick='updateNotificacao()'><i class='material-icons' id='iconNotif'>notifications_active</i><span class='badge' id='badge' style='background-color:#f44336;'>$quantidade</span>Notificação</a>";
            echo "<ul class='dropdown-menu' id='addNotification'>";
                $i = 0;
                while(isset($notif_conteudo[$i])){
                    if($i>0){
                        echo "<li role='separator' class='divider'></li>";
                    }
                    echo "<li class='notificacao' id='".$notif_conteudo[$i]['id']."'>".$notif_conteudo[$i]['conteudo']."</li>";//Adiciona as notificações
                    $i++;
                }
            echo "</ul>";
        } else { //Não tem notificação
            echo "<a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false' style='margin-left: -4px;color: #343;text-decoration: none;' onclick='updateNotificacao()'><i class='material-icons' id='iconNotif'>notifications</i><span class='badge dnone' id='badge' style='background-color:#f44336;'></span> Notificação</a><ul class='dropdown-menu' id='addNotification'><li class='notificacao'>Nenhuma notificação</li></ul>";      
        }
    ?>
</li>