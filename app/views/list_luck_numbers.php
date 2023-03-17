<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- os meus clientes -->
        <div class="col-12 p-3 bg-white">
                <div class="col">
                    <h3><strong>Números da Sorte</strong></h3>
                </div>
                <div class="row">
                <div class="col">
                    <h5><i class="fa-solid fa-user-tie me-2"></i>Usuário: <strong><?= $user->name ?></strong></h5>
                </div>
                <div class="col">
                    <!--  Encoda o array para JSON e guarda no campo input   -->
                    <?php $json_data = json_encode($luck_numbers, JSON_UNESCAPED_UNICODE); ?>
    
                    <!--  Envia os dados por esse form/button -->
                    <form action="?ct=lucknumber&mt=export_csv&id=luck_numbers" method="post">
                        <div class="col text-end">
                            <input type="hidden" name="data" value="<?php echo htmlentities($json_data); ?>">
                            <button type="submit" name="export_csv" class="btn btn-secondary">
                                <i class="fa-regular fa-file-excel me-2"></i>Exportar para CSV
                            </button>
                        </div>
                    </form>
                </div>
<!--                 <div class="col text-end">
                    <a href="#" class="btn btn-secondary"><i class="fa-solid fa-upload me-2"></i></i>Carregar ficheiro</a>
                    <a href="?ct=agent&mt=new_client_frm" class="btn btn-secondary"><i class="fa-solid fa-plus me-2"></i>Novo cliente</a>
                </div> -->
            </div>
            <hr>
            <?php if(empty($luck_numbers) ): ?>
                <p class="my-5 text-center opacity-75">Não existem cupons registados.</p>
            <?php else: ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Número da Sorte</th>
                            <th class="text-center">Sexo</th>
                            <th class="text-center">Data da criação</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($luck_numbers as $luck_number): ?>
                        <tr>
                        <td class="w-25 text-center"><?= $luck_number->name ?></td>
                            <td class="w-50 text-center"><?= $luck_number->hash ?></td>
                            <td class=" text-center"><?= $luck_number->sex ?></td>
                            <td class="w-25 text-center"><?= $luck_number->created_at ?? '' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col">
                        <p class="mb-5">Total: <strong><?=count($luck_numbers)?></strong></p>
                    </div>
  <!--                   <div class="col text-end">
                        <a href="#" class="btn btn-secondary">
                            <i class="fa-regular fa-file-excel me-2"></i>Exportar para XLSX
                        </a>
                    </div> -->
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>