<?php ?>

<div class="container-fluid">
    <div class="col-md-12">
        <span>Vælg at sortere efter:</span>
        <button data-state="ready" date-action="pro_sort_level" onclick="pro_sort_level()"class="btn-primary">Level</button>
        <button data-state="ready" date-action="pro_sort_date" onclick="pro_sort_dato()" class="btn-primary">Dato</button>
        <button data-state="ready" date-action="pro_sort_site" onclick="pro_sort_site())" class="btn-primary">Site</button>
        <button data-state="ready" date-action="pro_sort_all" onclick="pro_sort_all()" class="btn-primary">Vis alt</button>
    </div>
</div>

<div class="container-fluid overview-table">
    <div id="test" class="col-md-12">
    </div>
</div>
