{extends file=$conf->root_path|cat:"/templates/main.tpl"}

{block name=footer}
	<p style="margin-top: 20px; opacity: 0.8;">
		<i class="fas fa-code"></i> Zadanie 4 - Obiektowość
	</p>
{/block}

{block name=content}

<div class="calc-form">
	<h2><i class="fas fa-calculator"></i> Oblicz swoją ratę</h2>
	
	{* Wyświetlenie komunikatów i wyniku przed formularzem *}
	<div class="messages">
		{* wyświetlenie listy błędów, jeśli istnieją *}
		{if $msgs->isError()}
			{foreach $msgs->getErrors() as $err}
				<div class="err">
					<i class="fas fa-exclamation-circle"></i>
					<span>{$err}</span>
				</div>
			{/foreach}
		{/if}

		{* wyświetlenie listy informacji, jeśli istnieją *}
		{if $msgs->isInfo()}
			{foreach $msgs->getInfos() as $inf}
				<div class="inf">
					<i class="fas fa-info-circle"></i>
					<span>{$inf}</span>
				</div>
			{/foreach}
		{/if}

		{* wyświetlenie wyniku *}
		{if isset($res->monthly_payment)}
			<div class="res">
				<i class="fas fa-check-circle"></i>
				<span>Miesięczna rata: <strong>{$res->monthly_payment} zł</strong></span>
			</div>
		{/if}
	</div>

	{if !isset($hide_intro) || $hide_intro == false}
		<p style="text-align: center; color: #666; margin-bottom: 30px;">
			<i class="fas fa-hand-point-down"></i>
			Wprowadź dane kredytu, aby poznać wysokość miesięcznej raty
		</p>
	{/if}

	<form action="{$conf->app_url}/app/calc.php" method="post">
		<div class="form-group">
			<label for="loan">
				<i class="fas fa-money-bill-wave"></i> Kwota kredytu (zł)
			</label>
			<input 
				id="loan" 
				type="text" 
				placeholder="np. 250 000" 
				name="loan" 
				value="{$form->loan}"
			>
		</div>
		
		<div class="form-group">
			<label for="term">
				<i class="fas fa-calendar-alt"></i> Okres kredytowania
			</label>
			<select id="term" name="term">
				<option value="10" {if isset($form->term) && $form->term == 10}selected{/if}>10 lat</option>
				<option value="20" {if isset($form->term) && $form->term == 20}selected{/if}>20 lat</option>
				<option value="25" {if isset($form->term) && $form->term == 25}selected{/if}>25 lat</option>
				<option value="30" {if isset($form->term) && $form->term == 30}selected{/if}>30 lat</option>
			</select>
		</div>
		
		<div class="form-group">
			<label for="rate">
				<i class="fas fa-percent"></i> Oprocentowanie: <span id="rate-value">{if isset($form->rate)}{$form->rate}{else}11{/if}</span>%
			</label>
			<input 
				id="rate" 
				type="range" 
				name="rate" 
				min="2" 
				max="20" 
				value="{if isset($form->rate)}{$form->rate}{else}11{/if}"
				oninput="document.getElementById('rate-value').textContent = this.value"
			>
			<datalist id="values">
				{for $i=2 to 20 step 2}
					<option value="{$i}">{$i}%</option>
				{/for}
			</datalist>
		</div>

		<button type="submit" class="btn-submit">
			<i class="fas fa-calculator"></i> Oblicz miesięczną ratę
		</button>
	</form>
</div>

{/block}