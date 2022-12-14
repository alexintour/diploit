<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 
    Traduzione italiana a cura di webdiplomacy.it
 */

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * @package Base
 * @subpackage Forms
 */
 print libHTML::pageTitle('Crea una nuova partita','Inizia una nuova partita. Sei tu a deciderne il nome, la variante, la durata dei turni e il valore.');
?>


<form method="post">
<ul class="formlist">

	<li class="formlisttitle">
		Nome:
	</li>
	<li class="formlistfield">
		<input type="text" name="newGame[name]" value="" size="30">
	</li>
	<li class="formlistdesc">
		Scegli il nome da dare alla tua partita
	</li>

	<li class="formlisttitle">
		Durata del turno: (5 minuti - 10 giorni)
	</li>
	<li class="formlistfield">
		<select name="newGame[phaseMinutes]" onChange="document.getElementById('wait').selectedIndex = this.selectedIndex">
		<?php
			$phaseList = array(5, 10, 15, 20, 30,
				60, 120, 240, 360, 480, 600, 720, 840, 960, 1080, 1200, 1320,
				1440, 2160, 2880, 4320, 5760, 7200, 8640, 10080, 14400, 1440+60, 2880+60*2);

			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	<li class="formlistdesc">
	  Il tempo a disposizione dei giocatori per la diplomazia e l'inserimento degli ordini.<br />
      Tempi pi?? lunghi permettono decisioni pi?? accurate e diplomazia pi?? attenta, ma fanno durare di pi?? la partita. <br />
      Tempi pi?? corti rendono la partita pi?? veloce, ma i giocatori devono controllare la partita frequentemente se non volgiono perdere turni o ritrovarsi in sommossa.<br />

		<strong>Standard:</strong> 1 giorno
	</li>

	<li class="formlisttitle">
		Valore puntata: (5<?php print libHTML::points(); ?>-
			<?php print $User->points.libHTML::points(); ?>)
	</li>
	<li class="formlistfield">
		<input type="text" name="newGame[bet]" size="7" value="<?php print $formPoints ?>" />
	</li>
	<li class="formlistdesc">
		La puntata necessaria per iscriversi a questa partita. Questa ?? la quantit?? di punti che tutti i giocatori, te incluso, metterete sul "piatto" (<a href="points.php" class="light">Cosa sono i punti?</a>).<br /><br />

		<strong>Standard:</strong> <?php print $defaultPoints.libHTML::points(); ?>
	</li>
	
		<?php
if( count(Config::$variants)==1 )
{
	foreach(Config::$variants as $variantID=>$variantName) ;

	$defaultVariantName=$variantName;

	print '<input type="hidden" name="newGame[variantID]" value="'.$variantID.'" />';
}
else
{
?>
	<li class="formlisttitle">Variante:</li>
	<li class="formlistfield">
	
	<script type="text/javascript">
	function setExtOptions(i){
		document.getElementById('countryID').options.length=0;
		switch(i)
		{
			<?php
			$checkboxes=array();
			$first='';
			foreach(Config::$variants as $variantID=>$variantName)
			{
				$Variant = libVariant::loadFromVariantName($variantName);
				$checkboxes[$variantName] = '<option value="'.$variantID.'"'.(($first=='')?' selected':'').'>'.l_t($variantName).'</option>';
				if($first=='') {
					$first='"'.$variantID.'"';
					$defaultName=l_t($variantName);
				}
				print "case \"".$variantID."\":\n";
				print 'document.getElementById(\'desc\').innerHTML = "<a class=\'light\'  href=\'variants.php?variantID='.$variantID.'\'>'.l_t($Variant->fullName).'</a><hr style=\'color: #aaa\'>'.l_t($Variant->description).'";'."\n";		
				print "document.getElementById('countryID').options[0]=new Option ('Random','0');";
				for ($i=1; $i<=count($Variant->countries); $i++)
					print "document.getElementById('countryID').options[".$i."]=new Option ('".$Variant->countries[($i -1)]."', '".$i."');";
				print "break;\n";		
			}	
			ksort($checkboxes);	
			?>	
		}
	}
	</script>
	
	<table><tr>
		<td	align="left" width="0%">
			<select name="newGame[variantID]" onChange="setExtOptions(this.value)">
			<?php print implode($checkboxes); ?>
			</select> </td>
		<td align="left" width="100%">
			<div id="desc" style="border-left: 1px solid #aaa; padding: 5px;"></div></td>
	</tr></table>
	</li>
	<li class="formlistdesc">
		Scegli la variante della mappa, tra quelle disponibili.<br /><br />

        Oppure clicca sul nome della variante, per avere maggiori informazioni sulla stessa.<br />
		
        <strong>Standard:</strong> <?php print $defaultName;?>
	</li>
<?php
}
?>
	<div style="display: none;">
	<li class="formlisttitle">Country assignment:</li>
	<li class="formlistfield">
		<select id="countryID" name="newGame[countryID]">
		</select>
	</li>

	<ul class="formlist">
		
	<li class="formlistdesc">
		Random distribution of each country, or players pick their country (gamecreator get's the selected country).<br /><br />
		<strong>Default:</strong> Random
	</li>
	</div>
	
		<script type="text/javascript">
	setExtOptions(<?php print $first;?>);
	</script>

</ul>


<div class="hr"></div>

<div id="AdvancedSettingsButton">
<ul class="formlist">
	<li class="formlisttitle">
		<a href="#" onclick="$('AdvancedSettings').show(); $('AdvancedSettingsButton').hide(); return false;">
		Impostazioni avanzate
		</a>
	</li>
	<li class="formlistdesc">
		Impostazioni avanzate che permettono ai giocatori pi?? esperti di personalizzare meglio le partite.
              
	</li>
</ul>
</div>

<div id="AdvancedSettings" style="<?php print libHTML::$hideStyle; ?>">

<h3>Impostazioni avanzate</h3>

<ul class="formlist">
<?php /*
if( count(Config::$variants)==1 )
{
	foreach(Config::$variants as $variantID=>$variantName) ;

	$StandardVariantName=$variantName;

	print '<input type="hidden" name="newGame[variantID]" value="'.$variantID.'" />';
}
else
{
?>
	<li class="formlisttitle">Variante:</li>
	<li class="formlistfield">
	<?php
	$checkboxes=array();
	$first=true;
	foreach(Config::$variants as $variantID=>$variantName)
	{
		if( $first )
			$StandardVariantName=$variantName;
		$Variant = libVariant::loadFromVariantName($variantName);
		$checkboxes[] = '<input type="radio" '.($first?'checked="on" ':'').'name="newGame[variantID]" value="'.$variantID.'"> '.$Variant->link();
		$first=false;
	}
	print '<p>'.implode('</p><p>', $checkboxes).'</p>';
	?>
	</li>
	<li class="formlistdesc">
		Scegli la variante della mappa, tra quelle disponibili.<br /><br />

		Oppure clicca sul nome della variante, per avere maggiori informazioni sulla stessa.<br /><br />

		<strong>Standard:</strong> <?php print $StandardVariantName; ?>
	</li>
<?php
}
*/?>

	<li class="formlisttitle">Divisione dei punti:</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[potType]" value="Points-per-supply-center" checked > Per numero di centri<br />
		<input type="radio" name="newGame[potType]" value="Winner-takes-all"> Vincitore intasca tutto
	</li>
	<li class="formlistdesc">
		Decidi se, in caso di vittoria, la posta in gioco verr?? suddivisa tra tutti i sopravissuti in base al numero dei centri posseduti o se sar?? il vincitore ad intascare l'intera posta in gioco (<a href="points.php#ppscwta" class="light">per saperne di pi??</a>).<br />
		In caso di patta, la posta viene divisa equamente tra tutti i sopravissuti indipendentemente dal numero di centri posseduti.<br /><br />

		<strong>Standard:</strong> Per numero di centri
	</li>

	<li class="formlisttitle">
		Giocatori anonimi:
	</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[anon]" value="No" checked>No
		<input type="radio" name="newGame[anon]" value="Yes">S??
	</li>
	<li class="formlistdesc">
		Se scegli s??, i nomi degli altri giocatori non saranno visibili fino alla fine della partita.<br /><br />

		<strong>Standard:</strong> No, i giocatori non sono anonimi
	</li>

	<li class="formlisttitle">
		Diplomazia:
	</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[pressType]" value="Regular" checked><strong>Permetti tutto</strong> - possibilit?? di inviare messaggi privati ai singoli giocatori e globali a tutti i giocatori contemporaneamente. <br />
		<input type="radio" name="newGame[pressType]" value="PublicPressOnly"><strong>Solo messaggi globali</strong> - nessun messaggio privato: solo comunicazioni che possono essere lette da tutti i giocatori. <br />
		<input type="radio" name="newGame[pressType]" value="NoPress"><strong>Nessuna diplomazia</strong> - nessuna possibilit?? di interagire con gli altri giocatori.<br />
	</li>
	<li class="formlistdesc">
		<br /><br /><strong>Standard:</strong> Permetti tutto
	</li>

	<li class="formlisttitle">
		Tempo di attesa dei nuovi giocatori: (5 minuti - 10 giorni)
	</li>
	<li class="formlistfield">
		<select id="wait" name="newGame[joinPeriod]">
		<?php
			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	<li class="formlistdesc">
		Le partite, che allo scadere del tempo di attesa, non hanno raccolto un numero sufficiente di giocatori, vengono cancellate.<br />
      	Per partite con turni brevi (5 minuti), ?? opportuno impostare una scadenza superiore. <br />
		<strong> Nota bene:</strong> In caso di turni lunghi (12+ ore) la partita inizia non appena viene raggiunto il numero di giocatori richiesto.<br /> In caso di turni brevi (5-10 minuti) la partita inizia esattamente alla scadenza del tempo di attesa; cos?? ?? possibile programmare l'inizio delle partite <em>live</em> per una determinata ora.
		
		<br /><br /><strong>Standard:</strong> Stesso tempo di un turno
	</li>

	<li class="formlisttitle">
		<img src="images/icons/lock.png" alt="Private" /> Password (facoltativa):
	</li>
	<li class="formlistfield">
		<ul>
			<li>Password: <input type="password" name="newGame[password]" value="" size="30" /></li>
			<li>Conferma: <input type="password" name="newGame[passwordcheck]" value="" size="30" /></li>
		</ul>
	</li>
	<li class="formlistdesc">
		<strong>Questa opzione non ?? obbligatoria.</strong> Se impostate una password, solo le persone che la conoscono saranno in grado di iscriversi alla partita.<br /><br />

		<strong>Standard:</strong> Nessuna password
	</li>
</ul>

</div>

<div class="hr"></div>

<p class="notice">
	<input type="submit" class="form-submit" value="Crea partita">
</p>
</form>
