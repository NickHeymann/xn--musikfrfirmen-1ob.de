import { NextRequest, NextResponse } from 'next/server';
import { writeFile, appendFile, mkdir } from 'fs/promises';
import { existsSync } from 'fs';
import path from 'path';

export async function POST(request: NextRequest) {
  try {
    const errorLog = await request.json();

    // Limit log entry size to prevent giant files
    const logEntry = JSON.stringify({
      ...errorLog,
      serverTimestamp: new Date().toISOString(),
    });

    if (logEntry.length > 10000) {
      // Skip giant log entries
      return NextResponse.json({ success: true, skipped: 'too large' });
    }

    // Create logs directory if it doesn't exist
    const logsDir = path.join(process.cwd(), 'logs');
    if (!existsSync(logsDir)) {
      await mkdir(logsDir, { recursive: true });
    }

    // Write to daily log file (with size limit)
    const today = new Date().toISOString().split('T')[0];
    const logFile = path.join(logsDir, `errors-${today}.log`);

    await appendFile(logFile, logEntry + '\n');

    // Also keep in memory for debug panel (last 50 errors)
    if (!global.errorLogs) {
      global.errorLogs = [];
    }
    global.errorLogs.push(errorLog);
    if (global.errorLogs.length > 50) {
      global.errorLogs = global.errorLogs.slice(-50);
    }

    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Failed to log error:', error);
    return NextResponse.json({ success: false }, { status: 500 });
  }
}

export async function GET() {
  // Return recent errors for debug panel
  const logs = global.errorLogs || [];
  return NextResponse.json({ logs });
}
