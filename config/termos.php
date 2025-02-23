		  <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                TERMOS DE ACEITAÇÃO
              </header>
              <div class="panel-body">
                <div class="form">
  	              <table class="table table-striped">
                <thead>
                  <tr>
                 </tr>
                  <tr>
                    <td>
					  
					  
<div class="container"><br/>
  <div class="form-group">
    <div class="checkbox">
      <label data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        <input class="form-check-input" type="checkbox" id="termos_filiacao"  value="<?=$cliente->termos_filiacao?>" checked/> Termos sobre a Filiação ao Clube 
      </label>
    </div>
  </div>
  <div id="collapseOne" aria-expanded="false" class="collapse">
    <textarea rows="2" class="form-control estiloinput" name="termos_filiacao"  disabled><?= isset($termos_filiacao) ? $termos_filiacao : ""; ?></textarea></div>
  
	  
  <div class="form-group">
    <div class="form-check form-switch">
      <label data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <input class="form-check-input" type="checkbox" id="termos_estatuto" name="termos_estatuto" value="<?=$cliente->termos_filiacao?>" checked/> Termos sobre o Estatuto do Clube 
      </label>
    </div>
  </div>
  <div id="collapseTwo" aria-expanded="false" class="collapse">
    <textarea rows="2" class="form-control estiloinput" name="termos_estatuto"  disabled><?= isset($termos_estatuto) ? $termos_estatuto : ""; ?></textarea>
  </div>
	  
  <div class="form-group">
    <div class="form-check form-switch">
      <label data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
        <input class="form-check-input" type="checkbox" id="termos_idoneidade" name="termos_idoneidade" value="<?=$cliente->termos_idoneidade?>" checked>Termos sobre a Idoneidade do Atirador Esportivo
      </label>
    </div>
  </div>
  <div id="collapseThree" aria-expanded="false" class="collapse">
    <textarea  rows="2"  class="form-control estiloinput" name="termos_idoneidade" disabled><?= isset($termos_idoneidade) ? $termos_idoneidade : ""; ?></textarea>
  </div>
</div> 
					  </td>
                  </tr>
                </tbody>
              </table>
            </section>
          </div>
        </div>