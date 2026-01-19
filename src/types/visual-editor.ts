// Visual Editor Type Definitions

export interface Block {
  id: string;
  type: string;
  props: Record<string, any>;
}

export interface PageContent {
  version: string;
  type: 'page';
  blocks: Block[];
}

export interface PageData {
  id: number;
  slug: string;
  title: string;
  content: PageContent;
  meta_title?: string;
  meta_description?: string;
}

export interface Page {
  id: number;
  slug: string;
  title: string;
  updated_at: string;
}

export interface CreatePageInput {
  title: string;
  slug?: string;
  content: PageContent;
  meta_title?: string;
  meta_description?: string;
}

export type UpdatePageInput = Partial<CreatePageInput>;

export interface MediaUploadResponse {
  url: string;
  filename: string;
}

export interface ComponentConfig {
  component: React.ComponentType<any>;
  schema: any; // Zod schema
  defaultProps: Record<string, any>;
  category: 'sections' | 'content' | 'layout';
  icon: string;
  label: string;
  description: string;
}

export type ComponentRegistry = Record<string, ComponentConfig>;
