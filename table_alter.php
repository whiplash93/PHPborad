<?php
	require_once("dbconfig.php");
?>
<form action="table_alter.php" method="post"><input name="phpMyAdmin" type="hidden" >

	<table border = 1>
		<tr>
			<td>필드</td>
			<td>
			<input name="field_name[0]" title="필드" class="textfield" id="field_0_1" type="text" size="30" maxlength="64" value="b_title">
			</td>
		</tr>
		<tr>
			<td>종류</td>
			<td>
				<select name="field_type[0]" id="field_0_2">
					<option value="INT">INT</option>
					<option selected="selected" value="VARCHAR">VARCHAR</option>
					<option value="TEXT">TEXT</option>
					<option value="DATE">DATE</option>
					
					<optgroup label="NUMERIC">
						<option value="TINYINT">TINYINT</option>
						<option value="SMALLINT">SMALLINT</option>
						<option value="MEDIUMINT">MEDIUMINT</option>
						<option value="INT">INT</option>
						<option value="BIGINT">BIGINT</option>
						<option value="-">-</option>
						<option value="DECIMAL">DECIMAL</option>
						<option value="FLOAT">FLOAT</option>
						<option value="DOUBLE">DOUBLE</option>
						<option value="REAL">REAL</option>
						<option value="-">-</option>
						<option value="BIT">BIT</option>
						<option value="BOOL">BOOL</option>
						<option value="SERIAL">SERIAL</option>
					</optgroup>
					
					<optgroup label="DATE and TIME">
						<option value="DATE">DATE</option>
						<option value="DATETIME">DATETIME</option>
						<option value="TIMESTAMP">TIMESTAMP</option>
						<option value="TIME">TIME</option>
						<option value="YEAR">YEAR</option>
					</optgroup>
					
					<optgroup label="STRING">
						<option value="CHAR">CHAR</option>
						<option selected="selected" value="VARCHAR">VARCHAR</option>
						<option value="-">-</option>
						<option value="TINYTEXT">TINYTEXT</option>
						<option value="TEXT">TEXT</option>
						<option value="MEDIUMTEXT">MEDIUMTEXT</option>
						<option value="LONGTEXT">LONGTEXT</option>
						<option value="-">-</option>
						<option value="BINARY">BINARY</option>
						<option value="VARBINARY">VARBINARY</option>
						<option value="-">-</option>
						<option value="TINYBLOB">TINYBLOB</option>
						<option value="MEDIUMBLOB">MEDIUMBLOB</option>
						<option value="BLOB">BLOB</option>
						<option value="LONGBLOB">LONGBLOB</option>
						<option value="-">-</option>
						<option value="ENUM">ENUM</option>
						<option value="SET">SET</option>
					</optgroup>
					
					<optgroup label="SPATIAL">
						<option value="GEOMETRY">GEOMETRY</option>
						<option value="POINT">POINT</option>
						<option value="LINESTRING">LINESTRING</option>
						<option value="POLYGON">POLYGON</option>
						<option value="MULTIPOINT">MULTIPOINT</option>
						<option value="MULTILINESTRING">MULTILINESTRING</option>
						<option value="MULTIPOLYGON">MULTIPOLYGON</option>
						<option value="GEOMETRYCOLLECTION">GEOMETRYCOLLECTION</option>
					</optgroup>    
				
				</select>
			</td>
		</tr>
		<tr>
			<td>길이/값</td>
			<td>
				<input name="field_length[0]" class="textfield" id="field_0_3" type="text" size="30" value="100">
			</td>
		</tr>
		<tr>
			<td>기본값</td>
			<td>
				<select name="field_default_type[0]">
					<option selected="selected" value="NONE">None</option>
					<option value="USER_DEFINED">As defined:</option>
					<option value="NULL">NULL</option>
					<option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
				</select>
				<br/>
				<input name="field_default_value[0]" class="textfield" id="field_0_4" type="text" size="12" value="">
			</td>
		<tr>
			<td>Null</td>
			<td>
				<input name="field_null[0]" id="field_0_7" type="checkbox" value="NULL">
			</td>
		</tr>
		<tr>
			<td>AUTO_INCREMENT</td>
			<td>
				<input name="field_extra[0]" id="field_0_8" type="checkbox" value="AUTO_INCREMENT">
			</td>
		</tr>
	</table>
	<input name="do_save_data" type="submit" value=" 저  장  " />
</form>