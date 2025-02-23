               <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Modalidade</th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if($cliente->plano_pgto == 'A'){?>
                  <tr>
                    <td>ANUIDADE</td>
                    <td>
                    <input type="number" class="form-control" id="valor_anuidade" name="valor_anuidade" style="width: 300px;" value="<?=$cliente->valor_anuidade?>">
                    <input type="date" class="form-control" id="data_pgto_anuidade" style="width: 300px;" name="data_pgto_anuidade" value="<?=$cliente->data_pgto_anuidade?>">
                    <select name="forma_pgto_anuidade" id="forma_pgto_anuidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_anuidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_anuidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_anuidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_anuidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_anuidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_anuidade" placeholder="Observação" id="obs_anuidade" style="width: 300px;"><?php echo $cliente->obs_anuidade ?></textarea>
                    </td>
                    <td>-</td>
					<td>-</td>
                  </tr>
                  <?php }else{?>
                  <tr>
                    <td>MÊS</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade	" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade	 === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade	 === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade	 === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade	 === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade	 === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                    </td>
                  </tr>
                  <?php }?>
                  <tr>
                    <td><a href='relatorios/mensalidades2024.php?id=<?=$cliente->id?>' class="btn btn-info" target="_blank">Relat&oacute;rio 2024</a></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>