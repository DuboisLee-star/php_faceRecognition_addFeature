               <table class="table table-striped">
                <thead>
                  <tr>
                    <th>M&ecirc;s</th>
                    <th>Valor</th>
                    <th>M&ecirc;ss</th>
                    <th>Valor</th>					
                  </tr>
                </thead>
                <tbody>
                  
                  <tr>
                    <td>Anuidade</td>
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
                  <tr>
                    <td>JAN</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                    <td>JUL</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                  </tr>
                  <tr>
                    <td>FEV</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                    <td>AGO</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>

                  </tr>
                  <tr>
                    <td>MAR</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                    <td>SET</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                  </tr>
                  <tr>
                    <td>ABR</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                    <td>OUT</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                  </tr>
				  <tr>
                    <td>MAI</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                   <TD>NOV</TD>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                  </tr>
				  <tr>
                    <td>JUN</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                    <td>DEZ</td>
                    <td>
                    <input type="number" class="form-control" id="valor_mensalidade" name="valor_mensalidade" style="width: 300px;" placeholder="Valor" value="<?=$cliente->valor_mensalidade?>">
                    <input type="date" class="form-control" id="data_pgto_mensalidade" style="width: 300px;" name="data_pgto_mensalidade" value="<?=$cliente->data_pgto_mensalidade?>">
                    <select name="forma_pgto_mensalidade" id="forma_pgto_mensalidade" class="form-control" style="width: 300px;">
                    <option value="">- forma pgto -</option>
                    <option value="Boleto"<?php if ($cliente->forma_pgto_mensalidade === 'Boleto') echo ' selected'; ?>>Boleto</option>
                    <option value="Cartao"<?php if ($cliente->forma_pgto_mensalidade === 'Cartao') echo ' selected'; ?>>Cartão</option>
                    <option value="Pix"<?php if ($cliente->forma_pgto_mensalidade === 'Pix') echo ' selected'; ?>>Pix</option>
                    <option value="Dinheiro"<?php if ($cliente->forma_pgto_mensalidade === 'Dinheiro') echo ' selected'; ?>>Dinheiro</option>
                    <option value="Transferência"<?php if ($cliente->forma_pgto_mensalidade === 'Transferência') echo ' selected'; ?>>Transferência</option>
                    </select>
					<textarea class="form-control" rows="3" name="obs_mensalidade" placeholder="Observação" id="obs_mensalidade" style="width: 300px;"><?php echo $cliente->obs_mensalidade ?></textarea></td>
                  </tr>
                  <tr>
                    <td><a href='relatorios/mensalidades.php?id=<?=$cliente->id?>' class="btn btn-info" target="_blank">Relat&oacute;rio Anual</a></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>