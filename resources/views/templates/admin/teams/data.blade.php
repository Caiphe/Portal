<div class="head">
    <div class="column-team-name">Team name</div>
    <div class="column-team-country">Country</div>
    <div class="column-team-owner">Team Owner</div>
    <div class="column-team-members">Members</div>
    <div class="column-team-apps">Apps</div>
    <div class="column-created_at">Created at</div>
    <div class="column-actions">Actions</div>
  </div>

  <div class="body body-container">
        

    @forelse ($teams as $team)
    <div class="each-team">
        <div class="value-team-name">
            <a href="{{ route('admin.team.show', $team) }}">
                {{ Str::limit($team->name, 20, '...')}}
            </a>
        </div>

        <div class="value-team-country">
           <img src="/images/locations/{{ $team->country }}.svg" src="team-{{ $team->country }}.svg"
                alt="{{ $team->country }}"/>
        </div>
        <div class="value-team-owner">{{ Str::limit($team->email, 20, '...')}}</div>
        <div class="value-team-members">{{ count($team->users) }}</div>
        <div class="value-team-apps">{{ count($team->apps) }}</div>
        <div class="value-team-created_at">{{ date('d M Y', strtotime($team->created_at)) }}</div>

        <div class="value-team-actions">
            <a href="{{ route('admin.team.show', $team) }}" class="actions-btn" rel="noreferrer">@svg('eye', "#0c678f") View</a>
            <a href="{{ route('admin.team.edit', $team->id) }}" class="actions-btn"> @svg('pencil', "#0c678f") Edit</a>
            <a class="actions-btn delete-team-btn" >@svg('trash', "#0c678f") Delete</a>

            <x-dialog-box class="team-deletion-confirm" dialogTitle="Delete team">
                <div class="data-container">
                    <span>
                        Are you sure you want to delete this team ? <br/>
                        <strong> {{ $team->name }} </strong>
                    </span>
                    <p>All team members and applications will be removed from the team.</p>
                </div>

                <div class="bottom-shadow-container button-container">
                    <form class="confirm-user-deletion-request-form" method="POST" action="{{ route('admin.team.delete', $team) }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="team_id" value="{{ $team->id }}" />
                        <input type="hidden" name="team_name" value="{{ $team->name }}" />
                        <button type="submit" class="confirm-deletion-btn btn primary">Confirm</button>
                        <button type="button" class="cancel" onclick="closeDialogBox(this);">Cancel</button>
                    </form>
                </div>
            </x-dialog-box>

        </div>

        <button class="sl-button reset team-mobile-action">
            @svg('more-vert', '#0c678f')
            @svg('chevron-right', '#0c678f')
        </button>

    </div>
    @empty
    <p>No teams available. Please try other filters. </p>
    @endforelse

  </div>

  {{ $teams->withQueryString()->links() }}
