import { useEffect, useState } from "react";
import "./App.css";
import axios from "axios";
import { Spin, Space, Button } from "antd";
import DeveloperList from "./DeveloperList";

function App() {
    const [developerData, setDeveloperData] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [weekToFinish, setWeekToFinish] = useState(0);
    useEffect(() => {
        getTasks();
    }, []);
    useEffect(() => {
        if (developerData.length > 0) {
            let tmpArray = [
                ...developerData?.map((developer) => {
                    return developer.tasks?.reduce(
                        (total, task) => total + task.duration,
                        0
                    );
                }),
            ];
            let maxValue = Math.max(...tmpArray);
            setWeekToFinish(maxValue / 45);
        }
    }, [developerData]);

    function assignTasks() {
        const GET_URL = "http://www.localhost:8000/api/v1/task/organize";
        setIsLoading(true);
        axios
            .get(GET_URL)
            .then(() => getTasks())
            .catch((error) => {
                console.error(error);
                setIsLoading(false);
            });
    }

    function getTasks() {
        const GET_URL = "http://www.localhost:8000/api/v1/task/get-tasks";
        setIsLoading(true);
        axios
            .get(GET_URL)
            .then((response) => {
                console.log(developerData);
                console.log(response.data);
                setDeveloperData(response.data);
            })
            .catch((error) => console.error(error))
            .finally(() => setIsLoading(false));
    }

    return (
        <div className="App">
            <div
                style={{
                    width: "80%",
                    height: "90%",
                    position: "absolute",
                    top: "50%",
                    right: "50%",
                    transform: "translate(50%,-50%)",
                }}
            >
                {isLoading ? (
                    <Space size="middle">
                        <Spin size="large" />
                    </Space>
                ) : developerData.some((e) => e.tasks.length > 0) ? (
                    <>
                        <h1>Task Bitirme Süresi {weekToFinish} Hafta</h1>
                        <DeveloperList {...{ developerData }} />{" "}
                    </>
                ) : (
                    <Button type="primary" onClick={assignTasks}>
                        Taskları Ata ve Minimum Süreyi Bul
                    </Button>
                )}
            </div>
        </div>
    );
}

export default App;
