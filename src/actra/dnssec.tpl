<h3>DNSSEC Verwaltung</h3>

{if $successful}
    <div class="alert alert-success">Änderung erfolgreich gespeichert!</div>
{/if}

<table class="table table-striped">
    <thead>
    <tr>
        <th>Key Tag</th>
        <th>Algorithmus</th>
        <th>Digest Type</th>
        <th>Digest</th>
        <th>Aktion</th>
    </tr>
    </thead>
    <tbody>
    {foreach $records as $record}
        <tr>
            <td>{$record.keyTag}</td>
            <td>{$record.algorithm}</td>
            <td>{$record.digestType}</td>
            <td style="word-break: break-all;">{$record.digest}</td>
            <td>
                <form method="post" action="clientarea.php?action=domaindetails&id={$domainid}&modop=custom&a=GetDNSSEC">
                    <input type="hidden" name="keytag" value="{$record.keyTag}" />
                    <button type="submit" name="delete" class="btn btn-danger btn-sm">Löschen</button>
                </form>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>

<h4>Neuen DS-Record hinzufügen</h4>
<form method="post" action="clientarea.php?action=domaindetails&id={$domainid}&modop=custom&a=GetDNSSEC">
    <div class="row">
        <div class="col-md-2"><input type="text" name="keytag" class="form-control" placeholder="Key Tag" required /></div>
        <div class="col-md-2">
            <select name="alg" class="form-control">
                <option value="8">8 (RSA/SHA-256)</option>
                <option value="13">13 (ECDSA P-256/SHA-256)</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="digestType" class="form-control">
                <option value="2">2 (SHA-256)</option>
            </select>
        </div>
        <div class="col-md-4"><input type="text" name="digest" class="form-control" placeholder="Digest Hash" required /></div>
        <div class="col-md-2"><button type="submit" name="add" class="btn btn-primary">Hinzufügen</button></div>
    </div>
</form>