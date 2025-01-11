<div class="row mb-3">
    <label class="col-2">Group Name</label>
    <div class="col-auto">:
        <span class="font-weight-bolder"><?= $group->nm_group; ?></span>
    </div>
</div>
<div class="row mb-3">
    <label class="col-2">Description</label>
    <div class="col-auto">:
        <span><?= $group->ket; ?></span>
    </div>
</div>
<table class="table table-hover">
    <thead>
        <tr class="table-light">
            <th>Menus</th>
            <th class="text-center" width="120px">Read</th>
            <th class="text-center" width="120px">Create</th>
            <th class="text-center" width="120px">Update</th>
            <th class="text-center" width="120px">Delete</th>
            <th class="text-center" width="120px">Full Access</th>
        </tr>
    </thead>
    <?= $this->menu_generator->group_menus('1'); ?>
</table>