import styled from "styled-components";

const SubHeader = () => {
    return (
        <Container>
            <Ul>
                <Li>RSPO</Li>
                <Li>WWF International</Li>
                <Li>WWF Tigers Alive Initiative</Li>
                <Li>World Resources Institute</Li>
                <Li>The Coral Triangle</Li>
            </Ul>
        </Container>
    );
};

export default SubHeader;

const Container = styled.nav`
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
`;

const Ul = styled.ul`
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
`;

const Li = styled.li`
    flex: 0 0 auto;
    -webkit-box-align: center;
    -webkit-box-pack: center;
    -webkit-tap-highlight-color: transparent;
    align-items: center;
    color: #636363;
    height: 100%;
    justify-content: center;
    text-decoration: none;
    -webkit-box-align: center;
    -webkit-box-pack: center;
    -webkit-tap-highlight-color: transparent;
    align-items: center;
    display: flex;
    font-size: 13px;
    line-height: 13px;
    margin: 0 40px;
    text-decoration: none;
    font-weight: bold;
    white-space: nowrap;
    font-family: "Montserrat";
`;
