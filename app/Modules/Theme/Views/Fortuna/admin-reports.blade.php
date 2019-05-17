<!-- Admin Reports::Start -->
@php
use App\Modules\Admin\Services\AdminService;
use App\Modules\Core\Entities\Core;

$adminService = Core::getService(AdminService::class);
@endphp
@if(!empty($adminService->sessionQueries) && is_array($adminService->sessionQueries))
    <table class="fortuna_table" width="50%">
        <tbody>
            <tr>
                <th colspan="2">Stats</th>
            </tr>
            <tr>
                <td>Controller Execution Time: </td>
                <td>{{ round(microtime(true) - LARAVEL_START, 3) }}s</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table class="fortuna_table" width="100%">
        <tbody>
        <tr>
            <th>No</th>
            <th>SQL</th>
            <th>Parameters</th>
            <th>Time</th>
        </tr>
        @php($idx = 0)
        @foreach($adminService->sessionQueries as $query)
            @php($idx++)
            <tr {{ ($idx % 2 == 1) ? 'class=odd' : '' }}>
                <td>{{ $idx }}</td>
                <td><pre style="font-size:8pt; font-family: 'Courier New'; word-wrap: break-word; width:800px;">{{ $query['sql'] }}</pre></td>
                <td><pre style="font-size:8pt; font-family: 'Courier New'; word-wrap: break-word;">{{ print_r($query['bindings'], true) }}</pre></td>
                <td>{{ $query['time'] }}</td>
            </tr>
        @endforeach
        @php($adminService->clearQueries())
        </tbody>
    </table>
@endif
<!-- Admin Reports::END -->