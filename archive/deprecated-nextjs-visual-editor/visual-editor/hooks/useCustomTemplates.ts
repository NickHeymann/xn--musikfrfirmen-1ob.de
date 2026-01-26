/**
 * Custom Templates Hook
 * 
 * Manages user-created templates in localStorage.
 * Provides CRUD operations for custom templates.
 */

import { useState, useEffect, useCallback } from 'react';
import type { BlockTemplate, TemplateCategory } from '../types/blockTemplate';
import type { Block } from '../types';

const STORAGE_KEY = 'visual-editor-custom-templates';

/**
 * Load custom templates from localStorage
 */
function loadCustomTemplates(): BlockTemplate[] {
  if (typeof window === 'undefined') return [];
  
  try {
    const stored = localStorage.getItem(STORAGE_KEY);
    return stored ? JSON.parse(stored) : [];
  } catch (error) {
    console.error('Failed to load custom templates:', error);
    return [];
  }
}

/**
 * Save custom templates to localStorage
 */
function saveCustomTemplates(templates: BlockTemplate[]): void {
  if (typeof window === 'undefined') return;
  
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(templates));
  } catch (error) {
    console.error('Failed to save custom templates:', error);
  }
}

export function useCustomTemplates() {
  const [customTemplates, setCustomTemplates] = useState<BlockTemplate[]>([]);

  // Load on mount
  useEffect(() => {
    setCustomTemplates(loadCustomTemplates());
  }, []);

  /**
   * Add a new custom template
   */
  const addCustomTemplate = useCallback(
    (name: string, description: string, category: TemplateCategory, blocks: Block[]): BlockTemplate => {
      const newTemplate: BlockTemplate = {
        id: `custom-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
        name,
        description,
        category,
        blocks,
        preview: '', // No preview for custom templates initially
        isCustom: true,
        metadata: {
          createdAt: new Date().toISOString(),
          createdBy: 'user',
          usageCount: 0,
        },
      };

      const updated = [...customTemplates, newTemplate];
      setCustomTemplates(updated);
      saveCustomTemplates(updated);
      
      return newTemplate;
    },
    [customTemplates]
  );

  /**
   * Delete a custom template
   */
  const deleteCustomTemplate = useCallback(
    (templateId: string): void => {
      const updated = customTemplates.filter((t) => t.id !== templateId);
      setCustomTemplates(updated);
      saveCustomTemplates(updated);
    },
    [customTemplates]
  );

  /**
   * Update template usage count
   */
  const incrementTemplateUsage = useCallback(
    (templateId: string): void => {
      const updated = customTemplates.map((template) => {
        if (template.id === templateId) {
          return {
            ...template,
            metadata: {
              createdAt: template.metadata?.createdAt || new Date().toISOString(),
              createdBy: template.metadata?.createdBy,
              usageCount: (template.metadata?.usageCount || 0) + 1,
              lastUsed: new Date().toISOString(),
            },
          };
        }
        return template;
      });
      
      setCustomTemplates(updated);
      saveCustomTemplates(updated);
    },
    [customTemplates]
  );

  /**
   * Clear all custom templates
   */
  const clearAllCustomTemplates = useCallback((): void => {
    setCustomTemplates([]);
    saveCustomTemplates([]);
  }, []);

  return {
    customTemplates,
    addCustomTemplate,
    deleteCustomTemplate,
    incrementTemplateUsage,
    clearAllCustomTemplates,
  };
}
