<?php

namespace App\Actions;

use App\Models\McpLog;
use Laravel\Mcp\Request;

class LogMcpRequest
{
    public function log(Request $request, string $primitiveType, string $primitiveName): McpLog
    {
        return McpLog::create([
            'user_id' => $request->user()?->id,
            'session_id' => $request->sessionId(),
            'primitive_type' => $primitiveType,
            'primitive_name' => $primitiveName,
            'input' => $this->truncateInput($request->all()),
        ]);
    }

    private function truncateInput(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->truncateInput($value);
            } elseif (is_string($value) && strlen($value) > 500) {
                $data[$key] = substr($value, 0, 500).' [truncated]';
            }
        }

        return $data;
    }
}
