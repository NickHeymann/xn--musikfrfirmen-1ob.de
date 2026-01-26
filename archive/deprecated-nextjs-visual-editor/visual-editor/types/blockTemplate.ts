/**
 * Block Templates System - Type Definitions
 * 
 * Defines types for pre-configured block layouts that users can insert with one click.
 */

import type { Block } from '../types';

/**
 * Template categories for organizing templates
 */
export type TemplateCategory = 
  | 'hero'          // Hero sections with headlines and CTAs
  | 'features'      // Feature grids and showcases
  | 'testimonials'  // Customer testimonials and reviews
  | 'cta'           // Call-to-action banners
  | 'custom';       // User-created templates

/**
 * Metadata for template creation and usage tracking
 */
export interface TemplateMetadata {
  createdAt: string;
  createdBy?: string;
  usageCount?: number;
  lastUsed?: string;
}

/**
 * Complete block template definition
 */
export interface BlockTemplate {
  /** Unique identifier */
  id: string;
  
  /** Display name */
  name: string;
  
  /** Description of what this template contains */
  description: string;
  
  /** Category for organization and filtering */
  category: TemplateCategory;
  
  /** Array of blocks that make up this template */
  blocks: Block[];
  
  /** Preview image URL (can be placeholder or screenshot) */
  preview: string;
  
  /** Optional metadata */
  metadata?: TemplateMetadata;
  
  /** Whether this is a system template or user-created */
  isCustom?: boolean;
}

/**
 * Filter state for template library
 */
export interface TemplateFilter {
  category: TemplateCategory | 'all';
  searchQuery: string;
}
