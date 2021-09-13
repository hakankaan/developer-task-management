import React from "react";
import { Collapse, Tag } from "antd";
import { ClockCircleOutlined } from "@ant-design/icons";
const { Panel } = Collapse;

const DeveloperList = ({ developerData }) => {
    return (
        <div>
            <Collapse>
                {developerData.map((developer) => {
                    return (
                        <Panel
                            header={`ID: ${developer.id}   Zorluk: ${
                                developer.difficulty
                            }   Toplam Süre: ${developer.tasks.reduce(
                                (total, task) => total + task.duration,
                                0
                            )}`}
                            key={developer.id}
                        >
                            {developer.tasks.map((task) => {
                                return (
                                    <Tag
                                        icon={<ClockCircleOutlined />}
                                        color={
                                            developer.difficulty ===
                                            task.difficulty
                                                ? "processing"
                                                : "yellow"
                                        }
                                    >
                                        {`TaskID: ${task.id}    Süre: ${task.duration}    Zorluk: ${task.difficulty}`}
                                    </Tag>
                                );
                            })}
                        </Panel>
                    );
                })}
            </Collapse>
        </div>
    );
};

export default DeveloperList;
