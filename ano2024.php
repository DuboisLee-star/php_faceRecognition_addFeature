               <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Modalidade</th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if($cliente->plano_pgto == 'A'){ ?>
                  <tr>
                    <td>ANUIDADE</td>
                    <td>
                    <input type="number" class="form-control" id="valor_anuidade" name="valor" style="width: 300px;" value="<?=$cliente->valor?>">
                    <input type="date" class="form-control" id="data_pgto_anuidade" style="width: 300px;" name="data" value="<?=$cliente->data_pgto?>">
                    <select name="forma_pgto" id="forma_pgto_anuidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs" placeholder="Observação" id="obs_anuidade" style="width: 300px;"><?php echo $cliente->obs?></textarea>
                    </td>
                    <td>-</td>
					<td>-</td>
                  </tr>
                  <? }else{ ?>
                  <tr>
                    <td>MÊS</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data" value="<?=$cliente->data_pgto?>">
                    <select name="forma_pgto" id="forma_pgto_mensalidade	" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto	 === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto	 === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto	 === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto	 === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto	 === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs" placeholder="Observação" id="obs" style="width: 300px;"><?php echo $cliente->obs ?></textarea></td>
                    </td>
                  </tr>
                  <?php }?>
                  <tr>
                    <td><a href='relatorios/mensalidades2024.php?id=<?=$cliente->id?>' class="btn btn-info" target="_blank">Relat&oacute;rio 2024</a></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>