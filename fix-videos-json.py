#!/usr/bin/env python3
"""
Fix German smart quotes in videos.json to make it valid JSON.
Replaces „" (German smart quotes) with regular quotes.
"""

import json
import sys
from pathlib import Path

def fix_smart_quotes(text: str) -> str:
    """Replace German smart quotes with regular quotes."""
    # German opening quote „ (U+201E) -> regular quote "
    text = text.replace('„', '"')
    # German closing quote " (U+201C) -> regular quote "
    text = text.replace('"', '"')
    # Also handle other smart quote variants
    text = text.replace('"', '"')  # U+201D
    text = text.replace('‚', "'")  # U+201A
    text = text.replace(''', "'")  # U+2018
    text = text.replace(''', "'")  # U+2019
    return text

def main():
    # Path to videos.json
    data_dir = Path(__file__).parent / 'data'
    videos_file = data_dir / 'videos.json'
    
    if not videos_file.exists():
        print(f"Error: {videos_file} not found")
        sys.exit(1)
    
    print(f"Reading {videos_file}...")
    
    # Read the file with UTF-8 encoding
    with open(videos_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    print(f"Original size: {len(content)} characters")
    
    # Fix smart quotes
    fixed_content = fix_smart_quotes(content)
    
    print(f"Fixed {len([c for c in content if c in '„""‚'''])} smart quotes")
    
    # Validate the fixed JSON
    try:
        videos = json.loads(fixed_content)
        print(f"✓ Valid JSON! Found {len(videos)} videos")
    except json.JSONDecodeError as e:
        print(f"✗ JSON still invalid: {e}")
        sys.exit(1)
    
    # Create backup
    backup_file = videos_file.with_suffix('.json.backup')
    print(f"Creating backup: {backup_file}...")
    with open(backup_file, 'w', encoding='utf-8') as f:
        f.write(content)
    
    # Write fixed JSON
    print(f"Writing fixed JSON to {videos_file}...")
    with open(videos_file, 'w', encoding='utf-8') as f:
        f.write(fixed_content)
    
    print("✓ Done! videos.json has been fixed.")
    print(f"Backup saved to: {backup_file}")

if __name__ == '__main__':
    main()
