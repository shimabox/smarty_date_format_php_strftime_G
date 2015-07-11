{foreach from=$targetWeeks item=weeks key=month}
  <div>
    <h5>
      <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>&nbsp;
      {$month}&nbsp;第{$weeks.weekCnt}週&nbsp;
      翌年に属{if $weeks.includeNextYear}する{else}さない{/if}&nbsp;
    </h5>
    <p class="text-muted">
      {foreach from=$weeks.days item=day}
        {if !$day->isIncludedNextMonth()}
          <mark>{$day}</mark>
        {else}
          {$day}
        {/if}
      {/foreach}
    </p>
    <div class="table-responsive">
      <table class="table table-bordered">
	    <thead>
          <tr class="info">
            <th>曜日</th>
            <th>date_format:"%Y/%m/%d"</th>
            <th>date_format:"%G/%m/%d"</th>
          </tr>
        </thead>
        {foreach from=$weeks.days item=day}
          <tbody>
            <tr>
              <td>{$day->getDayOfWeek()}</td>
              <td>{$day->getDay()|date_format:"%Y/%m/%d"}</td>
              <td{if $day->getDay()|date_format:"%Y%m%d" !== $day->getDay()|date_format:"%G%m%d"} class="danger"{/if}>{$day->getDay()|date_format:"%G/%m/%d"}</td>
            </tr>
          </tbody>
        {/foreach}
      </table>
    </div>
  </div>
{/foreach}