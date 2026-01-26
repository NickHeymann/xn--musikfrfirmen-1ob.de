"use client";

import { useState } from "react";
import { useEditor } from "../../context/EditorContext";
import { ChevronDown, ChevronUp, Trash2, Plus, User } from "lucide-react";
import { MediaUploader } from "../../components/MediaUploader";
import type { TeamMember } from "@/types";

export function TeamSectionEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor();

  const block = blocks.find((b) => b.id === selectedBlockId);
  if (!block || block.type !== "TeamSection") return null;

  // Team members are enriched from defaultBlockData.ts on load
  const members: TeamMember[] = (block.props.teamMembers as TeamMember[]) || [];

  const [expandedIndex, setExpandedIndex] = useState<number | null>(null);

  const handleMemberChange = (
    index: number,
    field: keyof TeamMember,
    value: string,
  ) => {
    const updated = [...members];
    if (field === "position") {
      updated[index] = {
        ...updated[index],
        [field]: value as "left" | "right",
      };
    } else {
      updated[index] = { ...updated[index], [field]: value };
    }
    updateBlock(block.id, { teamMembers: updated });
  };

  const handleAddMember = () => {
    const newMember: TeamMember = {
      name: "New Team Member",
      role: "Position",
      roleSecondLine: "Additional Role Info",
      image: "/images/team/placeholder.png",
      bioTitle: "Bio Title",
      bioText: "Team member bio text...",
      imageClass: `img${members.length + 1}`,
      position: members.length % 2 === 0 ? "left" : "right",
    };
    updateBlock(block.id, { teamMembers: [...members, newMember] });
    setExpandedIndex(members.length); // Auto-expand new member
  };

  const handleRemoveMember = (index: number) => {
    const updated = members.filter((_, i) => i !== index);
    updateBlock(block.id, { teamMembers: updated });
    if (expandedIndex === index) setExpandedIndex(null);
  };

  const toggleExpand = (index: number) => {
    setExpandedIndex(expandedIndex === index ? null : index);
  };

  return (
    <div className="block-editor">
      <h3 className="editor-title">Team Section</h3>

      <div className="team-list">
        {members.map((member, index) => {
          const isExpanded = expandedIndex === index;

          return (
            <div key={index} className="team-card-item">
              <div className="team-header" onClick={() => toggleExpand(index)}>
                <div className="team-header-left">
                  <div className="team-avatar">
                    {member.image ? (
                      <img
                        src={member.image}
                        alt={member.name}
                        className="team-avatar-img"
                      />
                    ) : (
                      <User size={20} />
                    )}
                  </div>
                  <div className="team-info">
                    <span className="team-name-preview">{member.name}</span>
                    <span className="team-role-preview">{member.role}</span>
                  </div>
                </div>
                <div className="team-actions">
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation();
                      handleRemoveMember(index);
                    }}
                    className="icon-button-small danger"
                    title="Remove team member"
                  >
                    <Trash2 size={14} />
                  </button>
                  {isExpanded ? (
                    <ChevronUp size={16} />
                  ) : (
                    <ChevronDown size={16} />
                  )}
                </div>
              </div>

              {isExpanded && (
                <div className="team-fields">
                  <div className="editor-field">
                    <label htmlFor={`member-name-${index}`}>Name</label>
                    <input
                      id={`member-name-${index}`}
                      type="text"
                      value={member.name}
                      onChange={(e) =>
                        handleMemberChange(index, "name", e.target.value)
                      }
                      className="editor-input"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`member-role-${index}`}>Role</label>
                    <input
                      id={`member-role-${index}`}
                      type="text"
                      value={member.role}
                      onChange={(e) =>
                        handleMemberChange(index, "role", e.target.value)
                      }
                      className="editor-input"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`member-role-second-${index}`}>
                      Role Second Line
                    </label>
                    <input
                      id={`member-role-second-${index}`}
                      type="text"
                      value={member.roleSecondLine}
                      onChange={(e) =>
                        handleMemberChange(
                          index,
                          "roleSecondLine",
                          e.target.value,
                        )
                      }
                      className="editor-input"
                    />
                  </div>

                  <MediaUploader
                    label="Profile Image"
                    value={member.image}
                    onChange={(file) => {
                      if (file) {
                        // Create local preview URL
                        // TODO: In production, upload to server and get URL
                        const url = URL.createObjectURL(file);
                        handleMemberChange(index, "image", url);
                      } else {
                        handleMemberChange(index, "image", "");
                      }
                    }}
                    accept="image/*"
                    maxSizeMB={2}
                    type="image"
                  />

                  <div className="editor-field">
                    <label htmlFor={`member-image-class-${index}`}>
                      Image Class
                    </label>
                    <input
                      id={`member-image-class-${index}`}
                      type="text"
                      value={member.imageClass}
                      onChange={(e) =>
                        handleMemberChange(index, "imageClass", e.target.value)
                      }
                      className="editor-input"
                      placeholder="img1"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`member-bio-title-${index}`}>
                      Bio Title
                    </label>
                    <input
                      id={`member-bio-title-${index}`}
                      type="text"
                      value={member.bioTitle}
                      onChange={(e) =>
                        handleMemberChange(index, "bioTitle", e.target.value)
                      }
                      className="editor-input"
                      placeholder="Der Kreative"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`member-bio-${index}`}>Bio Text</label>
                    <textarea
                      id={`member-bio-${index}`}
                      value={member.bioText}
                      onChange={(e) =>
                        handleMemberChange(index, "bioText", e.target.value)
                      }
                      className="editor-textarea"
                      rows={4}
                      placeholder="Team member biography..."
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`member-position-${index}`}>Position</label>
                    <select
                      id={`member-position-${index}`}
                      value={member.position}
                      onChange={(e) =>
                        handleMemberChange(index, "position", e.target.value)
                      }
                      className="editor-input"
                    >
                      <option value="left">Left</option>
                      <option value="right">Right</option>
                    </select>
                  </div>
                </div>
              )}
            </div>
          );
        })}
      </div>

      <button
        type="button"
        onClick={handleAddMember}
        className="add-member-button"
      >
        <Plus size={16} />
        <span>Add Team Member</span>
      </button>
    </div>
  );
}
