<h3>{$actraLang["dnssec.title"]|escape}</h3>

{if $successful}
    <div class="alert alert-success">{$actraLang["dnssec.saved"]|escape}</div>
{/if}

<table class="table table-striped">
    <thead>
    <tr>
        <th>{$actraLang["dnssec.keyTag"]|escape}</th>
        <th>{$actraLang["dnssec.algorithm"]|escape}</th>
        <th>{$actraLang["dnssec.digestType"]|escape}</th>
        <th>{$actraLang["dnssec.digest"]|escape}</th>
        <th>{$actraLang["dnssec.action"]|escape}</th>
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
                    <button type="submit" name="delete" class="btn btn-danger btn-sm">{$actraLang["dnssec.delete"]|escape}</button>
                </form>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>

<h4>{$actraLang["dnssec.addTitle"]|escape}</h4>
<form method="post" action="clientarea.php?action=domaindetails&id={$domainid}&modop=custom&a=GetDNSSEC">
    <div class="row">
        <div class="col-md-2"><input type="text" name="keytag" class="form-control" placeholder="{$actraLang["dnssec.keyTag"]|escape}" required /></div>
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
        <div class="col-md-4"><input type="text" name="digest" class="form-control" placeholder="{$actraLang["dnssec.digest"]|escape}" required /></div>
        <div class="col-md-2"><button type="submit" name="add" class="btn btn-primary">{$actraLang["dnssec.add"]|escape}</button></div>
    </div>
</form>