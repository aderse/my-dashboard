<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JiraService
{
    public function issuesAssignedToMe(int $limit = 100): array
    {
        $user = auth()->user();
        $jira_email = $user->jira_email ?? '';
        $jira_token = $user->jira_api_key ?? '';
        if (empty($jira_email) || empty($jira_token)) {
            return []; // no Jira credentials, return empty array
        }
        $url = config('services.jira.base').'/rest/api/3/search';

        $response = Http::timeout(10)
            ->withBasicAuth(
                $jira_email,
                $jira_token
            )
            ->get($url, [
                // JQL: everything assigned to *this* account, newest first
                'jql'        => 'assignee = currentUser() AND status NOT IN ("done", "Prod Complete/Done", "Prod Complete/ Done", "Obsolete", "Stalled") ORDER BY updated DESC',
                'fields'     => 'summary,status,priority,updated',
                'maxResults' => $limit,
            ])
            ->throw();                 // blow up if Jira grumbles

        // turn the response into an array of issues
        $issues = collect($response->json('issues', []))
            ->map(function ($issue) {
                return [
                    'key'      => $issue['key'],
                    'summary'  => $issue['fields']['summary'],
                    'status'   => $issue['fields']['status']['name'],
                    'priority' => $issue['fields']['priority']['name'] ?? 'None',
                    'updated'  => $issue['fields']['updated'],
                    'url'      => config('services.jira.base').'browse/'.$issue['key'],
                ];
            })
            ->toArray();
        return $issues;
    }
}
