<?php
	$js_database = '';
	if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' ) {
		if ( isset($DispInfo) )
			$html .= fn_GetTranslation('update_database');
			$html .= fn_HtmlLabel('ConnectMySql', 'ConnectMySql', 'ConnectMySql');
			$html .= fn_HtmlLabel('ConnectDatabase', 'ConnectDatabase', 'ConnectDatabase');
			$html .= fn_HtmlLabel('CreateTableRelai', 'CreateTableRelai', 'CreateTableRelai');
			$html .= fn_HtmlLabel('CreateTableBtn', 'CreateTableBtn', 'CreateTableBtn');
			$html .= fn_HtmlLabel('CreateTableAn', 'CreateTableAn', 'CreateTableAn');
			$html .= fn_HtmlLabel('CreateTableCnt', 'CreateTableCnt', 'CreateTableCnt');
			$html .= fn_HtmlLabel('CreateTableVarTxt', 'CreateTableVarTxt', 'CreateTableVarTxt');
			$html .= fn_HtmlLabel('CreateTableVariable', 'CreateTableVariable', 'CreateTableVariable');
			$html .= fn_HtmlLabel('AlterTableTraceDropAnneMoisJour', 'AlterTableTraceDropAnneMoisJour', 'AlterTableTraceDropAnneMoisJour');
			$html .= fn_HtmlLabel('AlterTableTraceAddTimezone', 'AlterTableTraceAddTimezone', 'AlterTableTraceAddTimezone');
			$html .= fn_HtmlLabel('AlterTableTraceAddMicrotime', 'AlterTableTraceAddMicrotime', 'AlterTableTraceAddMicrotime');
			$html .= fn_HtmlLabel('AlterTableRelaiEtat', 'AlterTableRelaiEtat', 'AlterTableRelaiEtat');
			$html .= fn_HtmlLabel('AlterTableBtnEtat', 'AlterTableBtnEtat', 'AlterTableBtnEtat');
			$html .= fn_HtmlLabel('AlterTableAnEtat', 'AlterTableAnEtat', 'AlterTableAnEtat');
			$html .= fn_HtmlLabel('AlterTableCntEtat', 'AlterTableCntEtat', 'AlterTableCntEtat');
			$html .= fn_HtmlLabel('AlterTableVariableEtat', 'AlterTableVariableEtat', 'AlterTableVariableEtat');
			$html .= fn_HtmlLabel('AlterTableTraceEtat', 'AlterTableTraceEtat', 'AlterTableTraceEtat');
			$js_database  .= 'AjaxMySql("action=connectmysql","ConnectMySql", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=connectdatabase","ConnectDatabase", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=createtablerelai","CreateTableRelai", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=createtablebtn","CreateTableBtn", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=createtablean","CreateTableAn", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=createtablecnt","CreateTableCnt", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=createtablevartxt","CreateTableVarTxt", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=createtablevariable","CreateTableVariable", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertabletracedropanneemoisjour","AlterTableTraceDropAnneMoisJour", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertabletraceaddtimezone","AlterTableTraceAddTimezone", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertabletraceaddmicrotime","AlterTableTraceAddMicrotime", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertablerelaietat","AlterTableRelaiEtat", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertablebtnetat","AlterTableBtnEtat", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertableanetat","AlterTableAnEtat", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertablecntetat","AlterTableCntEtat", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertablevariableetat","AlterTableVariableEtat", "input#controlerror");'.PHP_EOL;
			$js_database  .= 'AjaxMySql("action=altertabletraceetat","AlterTableTraceEtat", "input#controlerror");'.PHP_EOL;

	}

?>