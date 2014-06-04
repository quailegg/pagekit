@script('user', 'system/js/user/user.js', 'requirejs')

<form id="js-user" class="uk-form" method="post">

    <div class="pk-toolbar uk-clearfix">
        <div class="uk-float-left">

            <a class="uk-button uk-button-primary" href="@url.route('@system/user/add')">@trans('Add User')</a>
            <a class="uk-button pk-button-danger uk-hidden js-show-on-select" href="#" data-action="@url.route('@system/user/delete')">@trans('Delete')</a>

            <div class="uk-button-dropdown uk-hidden js-show-on-select" data-uk-dropdown="{ mode: 'click' }">
                <button class="uk-button" type="button">@trans('More') <i class="uk-icon-caret-down"></i></button>
                <div class="uk-dropdown uk-dropdown-small">
                    <ul class="uk-nav uk-nav-dropdown">
                        <li><a href="#" data-action="@url.route('@system/user/status', ['status' => 1])">@trans('Activate')</a></li>
                        <li><a href="#" data-action="@url.route('@system/user/status', ['status' => 0])">@trans('Block')</a></li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="uk-float-right uk-hidden-small">

            <select name="filter[status]">

                <option value="">@trans('- Status -')</option>
                <option value="new"@(filter['status'] == 'new' ? ' selected' )>@trans('New')</option>
                @foreach (statuses as id => status)
                <option value="@id"@(filter['status']|length && filter['status'] === id ? ' selected' )>@status</option>
                @endforeach
            </select>

            <select name="filter[role]">
                <option value="">@trans('- Role -')</option>
                @foreach (roles as role)
                <option value="@role.id"@(filter['role'] == role.id ? ' selected')>@role.name</option>
                @endforeach
            </select>

            <select class="uk-form-width-medium" name="filter[permission]">
                <option value="">@trans('- Permission -')</option>
                @foreach (permissions as ext => permission)
                <optgroup label="@ext">
                    @foreach (permission as id => perm)
                    <option value="@id"@(filter['permission'] == id ? ' selected')>@trans(perm['title'])</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>

            <input type="text" name="filter[search]" placeholder="@trans('Search')" value="@filter['search']">

        </div>
    </div>

    @if (users)
    <div class="uk-overflow-container">
        <table class="uk-table uk-table-hover uk-table-middle js-table-users">
            <thead>
                <tr>
                    <th class="pk-table-width-minimum"><input type="checkbox" class="js-select-all"></th>
                    <th colspan="2">@trans('User')</th>
                    <th class="pk-table-width-100 uk-text-center">@trans('Status')</th>
                    <th class="pk-table-width-200">@trans('Email')</th>
                    <th class="pk-table-width-100">@trans('Roles')</th>
                </tr>
            </thead>
            <tbody>
                @foreach (users as user)
                <tr>
                    <td><input class="js-select" type="checkbox" name="ids[]" value="@user.id"></td>
                    <td class="pk-table-width-minimum">
                        <img class="uk-img-preserve uk-border-circle" width="40" height="40" alt="" data-avatar="@user.email">
                    </td>
                    <td class="uk-text-nowrap">
                        <a href="@url.route('@system/user/edit', ['id' => user.id])">@user.username</a>
                        <div class="uk-text-muted">@user.name</div>
                    </td>
                    <td class="uk-text-center">
                        @if (!user.status && !user.access && user.activation)
                        <a href="#" class="uk-icon-circle" data-action="@url.route('@system/user/status', ['ids[]' => user.id, 'status' => 1])" title="@trans('New')"></a>
                        @else
                        <a href="#" class="uk-icon-circle uk-text-@( user.status ? 'success' : 'danger' )" data-action="@url.route('@system/user/status', ['ids[]' => user.id, 'status' => user.status ? 0 : 1])" title="@user.statusText"></a>
                        @endif
                    </td>
                    <td>
                        <a href="mailto:@user.email">@user.email</a>@(app.option.get('system:user.require_verification') && user.get('verified') ? ' <i title="'~trans('Verified Email Address')~'" class="uk-icon-check"></i>')
                    </td>
                    <td>
                        @if (user.roles|length == 1)
                            @user.roles|implode('')
                        @else
                            @user.roles|slice(1)|implode(', ')
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="uk-alert uk-alert-info">@trans('No user found.')</p>
    @endif

    @token()

</form>
