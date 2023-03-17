<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-8 col-10 my-3">
            <div class="d-flex text-center justify-content-center my-2">
                <h2 class="text-white"><strong>Área de Sorteios</strong></h2>
            </div>
            <div class="card loginCard p-4">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-sm-4 justify-content-center text-center">
                            <label class="text-white" for=""><strong> Estado: </strong></label>
                            <?php if($sweepstake_status): ?>
                                <label class="btn btn-success w-full my-2">Realizado </label>
                            <?php else: ?>
                                <label class="btn btn-danger w-full my-2"> Não Realizado </label>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                    <div class="col-sm-4 text-center">
                        <button onclick="sweepstake_handlers('raffle')" class="btn btn-primary-bg btn-secondary w-100 my-2" id="raffle" name="raffle">Sortear</button>
                    </div>
                    </div>
                    <div class="row justify-content-center">
                    <div class="col-sm-4 text-center">
                        <button onclick="sweepstake_handlers('announce_sweepstake_winner')" class="btn btn-primary-bg btn-secondary w-100 my-2" id="raffle" name="raffle">Anunciar Vencedor</button>
                    </div>
                    </div>
                    <div class="row justify-content-center">
                    <div class="col-sm-4 text-center">
                    <button onclick="sweepstake_handlers('enableSweepstake')" class="btn btn-primary-bg btn-secondary w-100 my-2" id="raffle" name="raffle">Habilitar Novo Sorteio</button>
                    </div>
                    </div>
                    
                    <?php if(!empty($sweepstake_message) AND !$sweepstake_message['status']): ?>
                        <div class="alert alert-danger p-2 my-2 text-center">
                            <div><?= $sweepstake_message['message'] ?></div>
                    </div>   
                    <?php endif;?> 

                    <?php if(!empty($sweepstake_message) AND $sweepstake_message['status']): ?>
                        <div class="alert alert-success p-2 my-2 text-center">
                            <div><?= $sweepstake_message['message'] ?></div>
                    </div>   
                    <?php endif;?> 

                    <?php if(!empty($sweepstake_winner)): ?> 
                        <div class="alert alert-success p-2 my-2 text-center">
                            <h2 class="btn btn-success w-25">Vencedor!</h2>
                                <?php foreach($sweepstake_winner as $sweepstake): ?>
                                    <div><?= $sweepstake ?></div>
                                    <?php endforeach; ?>
                                </div>   
                                <?php endif;?>    
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sorteios Realizados -->
                    <div class="col-12 p-3 bg-white">
                        
                        <div class="row">
                            <div class="col">
                                <h5><i class="fa-solid fa-user-tie me-2"></i><strong>Sorteios realizados: </strong></h5>
                            </div>

                           <!--  Encoda o array para JSON e guarda no campo input   -->
                            <?php $json_data = json_encode($sweepstakes_data, JSON_UNESCAPED_UNICODE); ?>
                            
                           <!--  Envia os dados por esse form/button -->
                            <form action="?ct=sweepstake&mt=export_csv&id=sweepstakes" method="post">
                                <div class="col text-end">
                                    <input type="hidden" name="data" value="<?php echo htmlentities($json_data); ?>">
                                    <button type="submit" name="export_csv" class="btn btn-secondary">
                                        <i class="fa-regular fa-file-excel me-2"></i>Exportar para CSV
                                    </button>
                                </div>
                            </form>
                    <!--                 <div class="col text-end">
                            <a href="#" class="btn btn-secondary"><i class="fa-solid fa-upload me-2"></i></i>Carregar ficheiro</a>
                            <a href="?ct=agent&mt=new_client_frm" class="btn btn-secondary"><i class="fa-solid fa-plus me-2"></i>Novo cliente</a>
                        </div> -->
                    </div>
                    <hr>
                    <?php if(empty($sweepstakes_data) ): ?>
                        <p class="my-5 text-center opacity-75">Não existem sorteios realizados.</p>
                    <?php else: ?>
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="w-25 text-center">Nome do Vencedor</th>
                                    <th class="w-25 text-center">Numero da Sorte</th>
                                    <th class="w-25 text-center">Data do Sorteio</th>
                                   <!--  <th class="w-25 text-center">Ações</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($sweepstakes_data as $sweepstake): ?>
                                <tr>
                                    <td class="text-center"><?= $sweepstake->name ?></td>
                                    <td class="text-center"><?= $sweepstake->hash ?></td>
                                    <td class="text-center"><?= $sweepstake->created_at ?></td>
                                   <!--  <td class="text-center"> -->
                                       <!--  <a href="?ct=coupon&mt=sweepstake_coupons&id=<?=$sweepstake->id ?>"><i class="fa-regular fa-pen-to-square me-2"></i>Cupons</a> -->
                                    <!--  <span class="mx-2 opacity-50">|</span> -->
                    <!--                                 <a href="?ct=agent&mt=edit_sweepstake&id=<?=$sweepstake->id ?>"><i class="fa-regular fa-pen-to-square me-2"></i>Editar</a>
                                        <span class="mx-2 opacity-50">|</span> -->
                    <!--                                 <a href="?ct=agent&mt=delete_sweepstake&id=<?=$sweepstake->id ?>"><i class="fa-solid fa-trash-can me-2"></i>Eliminar</a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col">
                                <p class="mb-5">Total: <strong><?=count($sweepstakes_data)?></strong></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                    </div>
                </div>
            </div>
    </div>
</div>