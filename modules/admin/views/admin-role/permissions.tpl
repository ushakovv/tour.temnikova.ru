<style>
	.rules label {
		font-weight: normal;
		margin-right: 20px;
	}
	.rules .center {
		text-align: center;
	}
</style>
{*<a href="#" onclick="return checkAll(true)">Выбрать все</a>
<a href="#" onclick="return checkAll(false)" style="margin-left: 20px;">Отменить все</a>*}
<div id="permissions_block">
	{foreach $data as $group}
		<div class="rules">
			<h4>{$group['name']}</h4>
			<table class="table" cellspacing="0">
				<tr>
					<th style="width: 128px;">Раздел</th>
					<th>Создание</th>
					<th>Просмотр</th>
					<th>Редактирование</th>
					<th>Удаление</th>
				</tr>
				{foreach $group['sections'] as $section}
					<tr>
						<td><a href="/admin/{$section['uri']}" target="_blank">{$section['name']}</a></td>
						
						<td class="center">
							<input type="checkbox" name="Section_permissions[{$section['id']}][c]" class="rules"
							       id="Section_permissions_{$section['id']}_c" {if $section['c']}checked="true"{/if}
							       title="Создание" {if !$section['is_crud_section']}style="display: none;"{/if}>
						</td>
						<td class="center">
							<input type="checkbox" name="Section_permissions[{$section['id']}][r]" class="rules"
							       id="Section_permissions_{$section['id']}_r" {if $section['r']}checked="true"{/if}
							       title="Просмотр" {if !$section['is_crud_section']}style="display: none;"{/if}>
						</td>
						<td class="center">
							<input type="checkbox" name="Section_permissions[{$section['id']}][u]" class="rules"
							       id="Section_permissions_{$section['id']}_u" {if $section['u']}checked="true"{/if}
							       title="Редактирование" {if !$section['is_crud_section']}style="display: none;"{/if}>
						</td>
						<td class="center">
							<input type="checkbox" name="Section_permissions[{$section['id']}][d]" class="rules"
							       id="Section_permissions_{$section['id']}_d" {if $section['d']}checked="true"{/if}
							       title="Удаление" {if !$section['is_crud_section']}style="display: none;"{/if}>
						</td>
					</tr>
				{/foreach}
			</table>
		</div>
	{/foreach}
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('input.full_rules').change(function(){
			var _t = $(this);
			var checked = false;
			if(_t.is(':checked')) {
				checked = true;
			}
			_t.parents('tr').find('input.rules').prop('checked', checked);
		});

		$('input.rules').change(function(){
			var _t = $(this);
			var checked = true;
			if(_t.parents('tr').find('input.rules:checked').length!=4) {
				checked = false;
			}
			_t.parents('tr').find('input.full_rules').prop('checked', checked);
		});
	});

	checkAll = function(checked) {
		$('#permissions_block input').prop('checked', checked);
		return false;
	}
</script>