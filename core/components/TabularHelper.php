<?php

namespace core\components;


use yii\base\Model;

class TabularHelper {

	public static function render($form, $model, $relation, $template, $label = null){
		$items = array();
		$itemRows = $model->$relation;

		foreach($itemRows as $row){
			$items[$row->id] = $row;
		}

		$relQueryGetter = "get" . $relation;
		$relQuery = $model->$relQueryGetter();
		$relModelClass = $relQuery->modelClass;


		$output = "";

		foreach($items as $index => $item){
			$output .= self::_renderTabularItem($form, $template, $index, $item);
		}

		$output = '<div class="tabular_list">'
			. $output
			. '</div><div class="col-sm-4 col-sm-offset-9"><button class="btn" onclick="Tabular.addItem(this);return false"><div class="glyphicon glyphicon-plus" style="cursor: pointer"></div>  Добавить</button></div>';

		//TODO: dynamic model detect

		$output = '<div class="tabular_block">'
			. $output
			. '<div class="tabular_blank" style="display: none">'
			. self::_renderTabularItem($form, $template, '{index}', new $relModelClass())
			. '</div></div>';

		if($label){
			$output = ' <div class="form-group field-question-text">
                            <label class="control-label col-sm-3" for="question-text">' . $label . '</label>
                            <div class="col-sm-6">
								' . $output . '<div class="help-block help-block-error "></div>
							</div>
						</div> ';
		}

		UpdateScenario::registerHandler(
			null,
			'\admin\components\TabularHelper',
			'update',
			[
				'relation' => $relation
			],
			true
		);

		return $output;
	}

	private static function _renderTabularItem($form, $template, $index, $model)
	{
		$tpl_tabular_item = '<div class="tabular_item"><div class="subform col-sm-11">{content}</div>
							<div class="col-sm-1">
								<div class="glyphicon glyphicon-remove" style="cursor: pointer" onclick="Tabular.deleteItem(this)"></div>
							</div></div>';
		$output = str_replace('{content}', \Yii::$app->view->render($template, ['index' => $index, 'model' => $model, 'form' => $form]), $tpl_tabular_item);

		return $output;
	}

	public static function update($model, $attribute, $value, $options = [])
	{
		$relation = $options['relation'];
		$items = array();
		$itemRows = $model->$relation;

		foreach($itemRows as $row){
			$items[$row->id] = $row;
		}

		$relQueryGetter = "get" . $relation;
		$relQuery = $model->$relQueryGetter();
		$relModelClass = $relQuery->modelClass;

		$relModel = new $relModelClass();
		$formName = $relModel->formName();


		$allPostData = \Yii::$app->request->post();
		$postData = isset($allPostData[$formName]) ? $allPostData[$formName] : [];

		foreach($items as $id => $item){
			if(isset($postData[$id])){
				$item->attributes = $postData[$id];
				$item->save();
			} else {
				$item->delete();
			}
		}

		$linkField = key($relQuery->link);

		foreach($postData as $id => $postItem){
			if(!isset($items[$id]) && $id != "{index}"){
				$newItem = new $relModelClass();
				$newItem->attributes = $postItem;
				$newItem->$linkField = $model->id;
				$newItem->save(false);
			}
		}

	}

} 